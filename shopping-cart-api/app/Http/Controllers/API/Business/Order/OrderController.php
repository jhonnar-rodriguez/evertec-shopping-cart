<?php namespace App\Http\Controllers\API\Business\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Order\CreateOrderRequest;
use App\Models\Business\Cart\Cart;
use App\Models\Business\Order\Order;
use App\Repositories\Business\Order\OrderRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * @var OrderRepositoryInterface
     */
    private $orderRepository;

    /**
     * OrderController constructor.
     *
     * @param OrderRepositoryInterface $orderRepository
     */
    public function __construct(OrderRepositoryInterface $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Start a payment process with PlaceToPay
     *
     * @param CreateOrderRequest $request
     * @param Cart $cart
     * @return JsonResponse
     */
    public function create(CreateOrderRequest $request, Cart $cart): JsonResponse
    {
        $order = $this->orderRepository->create($request, $cart);

        return $this->generalResponse(
            $order['data'],
            $order['message'],
            $order['status_code']
        );
    }

    /**
     * Get order using the request id
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function get(Request $request, Order $order): JsonResponse
    {
        $order = $this->orderRepository->get($request, $order);

        return $this->generalResponse(
            $order['data'],
            $order['message'],
            $order['status_code']
        );
    }

    /**
     * Get all orders in the system
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll(Request $request): JsonResponse
    {
        $orders = $this->orderRepository->getAll($request);

        return $this->generalResponse(
            $orders['data'],
            $orders['message'],
            $orders['status_code']
        );
    }

    /**
     * Generate a new cart with the same items of the given order
     *
     * @param Request $request
     * @param Order $order
     * @return JsonResponse
     */
    public function reOrder(Request $request, Order $order): JsonResponse
    {
        $reOrder = $this->orderRepository->reOrder($request, $order);

        return $this->generalResponse(
            $reOrder['data'],
            $reOrder['message'],
            $reOrder['status_code']
        );
    }

}
