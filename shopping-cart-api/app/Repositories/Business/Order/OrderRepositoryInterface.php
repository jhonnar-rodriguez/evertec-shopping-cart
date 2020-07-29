<?php namespace App\Repositories\Business\Order;

use App\Models\Business\Cart\Cart;
use App\Models\Business\Order\Order;
use App\Repositories\BaseInterface;
use Illuminate\Http\Request;

interface OrderRepositoryInterface extends BaseInterface
{
    /**
     * Start a payment process with PlaceToPay
     *
     * @param Request $request
     * @param Cart $cart
     * @return mixed
     */
    public function create(Request $request, Cart $cart);

    /**
     * Based on a given order id we need to recreate the shopping cart
     *
     * @param Request $request
     * @param Order $order
     * @return mixed
     */
    public function reOrder(Request $request, Order $order);

    /**
     * Get order using the request id
     *
     * @param Request $request
     * @param Order $order
     * @return mixed
     */
    public function get(Request $request, Order $order);

    /**
     * Get all orders in the system
     *
     * @param Request $request
     * @return mixed
     */
    public function getAll(Request $request);
}
