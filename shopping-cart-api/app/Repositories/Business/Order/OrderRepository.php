<?php namespace App\Repositories\Business\Order;

use App\Models\Business\Cart\Cart;
use App\Models\Business\Order\Order;
use App\Repositories\BaseRepository;
use App\Repositories\Business\Cart\CartRepositoryInterface;
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
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * OrderRepository constructor.
     *
     * @param Order $order
     * @param PlaceToPayService $placeToPayService
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct(
        Order $order,
        PlaceToPayService $placeToPayService,
        CartRepositoryInterface $cartRepository
    ) {
        $this->model = $order;
        $this->placeToPayService = $placeToPayService;
        $this->cartRepository = $cartRepository;
    }

    /**
     * @inheritDoc
     */
    public function create(Request $request, Cart $cart)
    {
        DB::beginTransaction();

        try {
            $loggedUserId = $request->user()->id;

            $userAlreadyHasOrder = $this->model->query()
                ->where('user_id', $loggedUserId)
                ->where('status', 'CREATED')
                ->first();

            if (empty($userAlreadyHasOrder) === false) {
                $message = "User already has an active order, please finish the order number: " .
                    "{$userAlreadyHasOrder->id} first and the try again!";

                return $this->response(
                    [],
                    $message,
                    config('business.http_responses.bad_request.code')
                );
            }

            # We need to create the order to link the reference to the redirect url
            $newOrder = $this->createOrderStub($request);
            if ($newOrder->save() === false) {
                return $this->response(
                    [],
                    "Something went wrong creating the order, please try again later",
                    config('business.http_responses.server_error.code')
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
            $initialData = new stdClass();
            $initialData->frontend_url = $request->redirect_base;
            $initialData->cart = $cart;
            $initialData->order = $newOrder;
            $initialData->request_ip = $request->ip();
            $initialData->user_agent = $request->userAgent();

            # Format the data and make the request to the PlaceToPay service
            $createOrder = $this->placeToPayService->createOrder($initialData);

            if ($createOrder['success'] === true) {
                # Search the required properties
                if (
                    isset($createOrder['request_id']) === true &&
                    isset($createOrder['process_url']) === true
                ) {
                    # Update the previous order to add the request_id and return_url
                    $newOrder->request_id = $createOrder['request_id'];
                    $newOrder->process_url = $createOrder['process_url'];
                    $newOrder->return_url = $createOrder['return_url'];
                    $newOrder->save();

                    # Delete the user cart so the user can continue shopping
                    $cart->delete();

                    $arrayToReturn = [
                        'message' => "Order was created successfully.",
                        'code' => config('business.http_responses.created.code'),
                        'data' => $newOrder->toArray(),
                    ];
                } else {
                    $arrayToReturn = [
                        'message' => 'Unexpected output from service, please try again later.',
                        'code' => config('business.http_responses.server_error.code'),
                        'data' => [],
                    ];
                }
            } else {
                $arrayToReturn = [
                    'message' => $createOrder['message'],
                    'code' => $createOrder['code'],
                    'data' => [],
                ];
            }

            DB::commit();

            return $this->response(
                $arrayToReturn['data'],
                $arrayToReturn['message'],
                $arrayToReturn['code']
            );
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error(
                "OrderRepository.create: Something went wrong creating the order. Details: " .
                $exception->getMessage()
            );

            return $this->response(
                [],
                "Something went wrong creating the order",
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function reOrder(Request $request, Order $order)
    {
        DB::beginTransaction();

        try {
            $loggedUserId = $request->user()->id;

            $userAlreadyHasOrder = $this->model->query()
                ->where('user_id', $loggedUserId)
                ->where('status', 'CREATED')
                ->first();

            if (empty($userAlreadyHasOrder) === false) {
                $message = "User already has an active order, please finish the order number: " .
                    "{$userAlreadyHasOrder->id} first and the try again!";

                return $this->response(
                    [],
                    $message,
                    config('business.http_responses.bad_request.code')
                );
            }

            $clientKey = substr(md5(time()), 0, 10);


            $newCart = $this->cartRepository->createCartStub($clientKey, $loggedUserId);

            if ($newCart->save() === false) {
                return $this->response(
                    [],
                    'Something went wrong re creating the cart, please try again later.',
                    config('business.http_responses.server_error.code')
                );
            }
            $cartItems = [];

            foreach ($order->items as $item) {
                $newItem = [
                    'cart_id' => $newCart->id,
                    'quantity' => $item->quantity,
                    'product_id' => $item->product_id,
                ];

                array_push($cartItems, $newItem);
            }

            $newCart->items()->insert($cartItems);

            DB::commit();

            return $this->response(
                [
                    'client_key' => $clientKey
                ],
                'Order was successfully re opened',
                config('business.http_responses.created.code')
            );
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error(
                "OrderRepository.reOpen: Something went wrong re opening the order. Details: " .
                $exception->getMessage()
            );

            return $this->response(
                [],
                "Something went wrong re opening the order",
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function get(Request $request, Order $order)
    {
        try {
            # Format the data and make the request to the PlaceToPay service
            $getOrder = $this->placeToPayService->getOrder(( int ) $order->request_id);

            if ($getOrder['success'] === true) {
                $allowedStatuses = [
                    'APPROVED' => 'PAYED',
                    'REJECTED' => 'REJECTED',
                ];

                # Update the order status in database if it the status is different from the oldest one
                $orderStatus = $getOrder['status'];
                if (in_array($orderStatus, $allowedStatuses) && $orderStatus !== $order->status) {
                    $order->status = $orderStatus;
                    $order->save();
                }

                $customOrderData = [
                    'request_id' => $order->request_id,
                    'process_url' => $order->process_url,
                    'return_url' => $order->return_url,
                    'total' => $order->total,
                    'user' => $order->user->full_name,
                    'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                    'status' => $orderStatus,
                ];

                $arrayToReturn = [
                    'message' => $getOrder['message'],
                    'code' => config('business.http_responses.success.code'),
                    'data' => [
                        'order' => $customOrderData,
                    ],
                ];
            } else {
                $arrayToReturn = [
                    'message' => $getOrder['message'],
                    'code' => config('business.http_responses.bad_request.code'),
                    'data' => [],
                ];
            }

            return $this->response(
                $arrayToReturn['data'],
                $arrayToReturn['message'],
                $arrayToReturn['code']
            );
        } catch (Exception $exception) {
            Log::error(
                "OrderRepository.get: Something went wrong getting the order. Details: " .
                $exception->getMessage()
            );

            return $this->response(
                [],
                "Something went wrong getting the order",
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * @inheritDoc
     */
    public function getAll(Request $request)
    {
        try {
            $loggedUser = $request->user();
            $ordersByUser = $loggedUser->orders;

            $formattedOrders = [];
            foreach ($ordersByUser as $order) {
                $newOrder = [
                    'id' => $order->id,
                    'request_id' => $order->request_id,
                    'process_url' => $order->process_url,
                    'return_url' => $order->return_url,
                    'total' => $order->total,
                    'name' => $loggedUser->full_name,
                    'created_at' => $order->created_at,
                    'status' => $order->status,
                ];

                array_push($formattedOrders, $newOrder);
            }

            return $this->response(
                $formattedOrders,
                "Orders were received",
                config('business.http_responses.success.code')
            );
        } catch (Exception $exception) {
            Log::error(
                "OrderRepository.getAll: Something went wrong getting all orders. Details: " .
                $exception->getMessage()
            );

            return $this->response(
                [],
                "Something went wrong getting all orders",
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * Create a new record in database
     *
     * @param Request $request
     * @return mixed
     */
    private function createOrderStub(Request $request)
    {
        $loggedUser = $request->user();

        # Order values
        $order = new $this->model();
        $order->user_id = $loggedUser->id;
        $order->total = round($request->order_total, 2);
        $order->status = $request->order_status;
        $order->request_id = null;
        $order->process_url = null;

        return $order;
    }
}
