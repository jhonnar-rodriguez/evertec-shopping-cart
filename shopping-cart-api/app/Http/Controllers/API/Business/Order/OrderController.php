<?php namespace App\Http\Controllers\API\Business\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Order\CreateOrderRequest;
use App\Models\Business\Cart\Cart;
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
    public function __construct( OrderRepositoryInterface $orderRepository )
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
    public function create( CreateOrderRequest $request, Cart $cart ): JsonResponse
    {
        $order = $this->orderRepository->create( $request, $cart );

        return $this->generalResponse(
            $order['data'],
            $order['message'],
            $order['status_code']
        );
    }

}
