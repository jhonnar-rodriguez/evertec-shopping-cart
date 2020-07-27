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
    public function create( Request $request, Cart $cart );

    /**
     * Get order using the request id
     *
     * @param Request $request
     * @param Order $order
     * @return mixed
     */
    public function get( Request $request, Order $order );

}
