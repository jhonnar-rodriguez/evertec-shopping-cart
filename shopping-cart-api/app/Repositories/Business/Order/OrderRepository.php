<?php namespace App\Repositories\Business\Order;

use App\Models\Business\Cart\Cart;
use App\Models\Business\Order\Order;
use App\Repositories\BaseRepository;
use App\Services\PlaceToPayService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use stdClass;

class OrderRepository extends BaseRepository implements OrderRepositoryInterface
{
    /**
     * @var PlaceToPayService
     */
    private $placeToPayService;

    /**
     * OrderRepository constructor.
     *
     * @param Order $order
     * @param PlaceToPayService $placeToPayService
     */
    public function __construct(
        Order $order,
        PlaceToPayService $placeToPayService
    )
    {
        $this->model = $order;
        $this->placeToPayService = $placeToPayService;
    }

    /**
     * @inheritDoc
     */
    public function create( Request $request, Cart $cart )
    {
        DB::beginTransaction();

        try
        {
            $frontendURL = config( 'environment.FRONTEND_URL' );

            if ( empty( $frontendURL ) === true )
            {
                return $this->response(
                    [],
                    'The frontend URL is not defined in the variables of the server, please add it and try again.',
                    config( 'business.http_responses.bad_request.code' )
                );
            }

            $loggedUserId = $request->user()->id;
            $userAlreadyHasOrder = $this->model->query()
                ->where( 'user_id',  $loggedUserId )
                ->where( 'status',  'CREATED' )
                ->first();

            if ( empty( $userAlreadyHasOrder ) === false )
            {
                $message = "User already has an active order, please finish the order number: " .
                    "{$userAlreadyHasOrder->id} first and the try again!";

                return $this->response(
                    [],
                    $message,
                    config( 'business.http_responses.bad_request.code' )
                );
            }

            # We need to create the order to link the reference to the redirect url
            $newOrder = $this->createOrderStub( $request );
            if ( $newOrder->save() === false )
            {
                return $this->response(
                    [],
                    "Something went wrong creating the order, please try again later",
                    config( 'business.http_responses.server_error.code' )
                );
            }

            # Add the items that belongs to the order
            $cartItems = $cart->items()
                ->select(['product_id', 'quantity'])
                ->get()
                ->toArray();

            $newOrder
                ->items()
                ->createMany($cartItems);

            # Required values to set up the request data
            $initialData               = new stdClass();
            $initialData->frontend_url = $frontendURL;
            $initialData->cart         = $cart;
            $initialData->order        = $newOrder;
            $initialData->request_ip   = $request->ip();
            $initialData->user_agent   = $request->userAgent();

            # Format the data and make the request to the PlaceToPay service
            $createOrder = $this->placeToPayService->createOrder( $initialData );

            if ( $createOrder['success'] === true )
            {
                # Search the required properties
                if (
                    isset( $createOrder['request_id'] ) === true &&
                    isset( $createOrder['process_url'] ) === true
                )
                {
                    # Update the previous order to add the request_id and return_url
                    $newOrder->request_id  = $createOrder['request_id'];
                    $newOrder->process_url = $createOrder['process_url'];
                    $newOrder->save();

                    $arrayToReturn = [
                        'message'   => "Order was created successfully.",
                        'code'      => config( 'business.http_responses.created.code' ),
                        'data'      => $newOrder->toArray(),
                    ];
                }
                else
                {
                    $arrayToReturn = [
                        'message'   => 'Unexpected output from service, please try again later.',
                        'code'      => config( 'business.http_responses.server_error.code' ),
                        'data'      => [],
                    ];
                }
            }
            else
            {
                $arrayToReturn = [
                    'message'   => $createOrder['message'],
                    'code'      => $createOrder['code'],
                    'data'      => [],
                ];
            }

            DB::commit();

            return $this->response(
                $arrayToReturn['data'],
                $arrayToReturn['message'],
                $arrayToReturn['code']
            );
        }
        catch ( Exception $exception )
        {
            DB::rollBack();

            Log::error(
                "OrderRepository.create: Something went wrong creating the order. Details: " .
                $exception->getMessage()
            );

            return $this->response(
                [],
                "Something went wrong creating the order",
                config( 'business.http_responses.server_error.code' )
            );

        }

    }

    /**
     * @inheritDoc
     */
    public function get( Request $request, Order $order )
    {
        try
        {
            # Format the data and make the request to the PlaceToPay service
            $getOrder = $this->placeToPayService->getOrder( ( int ) $order->request_id );

            if ( $getOrder['success'] === true )
            {
                $allowedStatuses = [
                    'APPROVED' => 'PAYED',
                    'REJECTED' => 'REJECTED',
                ];

                # Update the order status in database if it the status is different from the oldest one
                $orderStatus = $getOrder['status'];
                if ( in_array( $orderStatus, $allowedStatuses ) && $orderStatus !== $order->status )
                {
                    $order->status = $orderStatus;
                    $order->save();
                }

                # Remove the database status in order to keep the service response
                $customOrderData = $order->toArray();
                unset( $customOrderData['status'] );

                $arrayToReturn = [
                    'message'   => $getOrder['message'],
                    'code'      => config( 'business.http_responses.success.code' ),
                    'data'      => [
                        'status' => $orderStatus,
                        'order'  => $customOrderData,
                    ],
                ];
            }
            else
            {
                $arrayToReturn = [
                    'message'   => $getOrder['message'],
                    'code'      => config( 'business.http_responses.bad_request.code' ),
                    'data'      => [],
                ];
            }

            return $this->response(
                $arrayToReturn['data'],
                $arrayToReturn['message'],
                $arrayToReturn['code']
            );
        }
        catch ( Exception $exception )
        {
            Log::error(
                "OrderRepository.get: Something went wrong getting the order. Details: " .
                $exception->getMessage()
            );

            return $this->response(
                [],
                "Something went wrong getting the order",
                config( 'business.http_responses.server_error.code' )
            );

        }
    }

    /**
     * @inheritDoc
     */
    public function getAll( Request $request )
    {
        try
        {
            $ordersByUser = $request->user()->orders->toArray();

            return $this->response(
                $ordersByUser,
                "Orders were received",
                config( 'business.http_responses.success.code' )
            );
        }
        catch ( Exception $exception )
        {
            Log::error(
                "OrderRepository.getAll: Something went wrong getting all orders. Details: " .
                $exception->getMessage()
            );

            return $this->response(
                [],
                "Something went wrong getting all orders",
                config( 'business.http_responses.server_error.code' )
            );

        }
    }

    /**
     * Create a new record in database
     *
     * @param Request $request
     * @return mixed
     */
    private function createOrderStub( Request $request )
    {
        $loggedUser = $request->user();

        # Order values
        $order              = new $this->model();
        $order->user_id     = $loggedUser->id;
        $order->total       = round( $request->order_total, 2 );
        $order->status      = $request->order_status;
        $order->request_id  = null;
        $order->process_url = null;

        return $order;
    }

}
