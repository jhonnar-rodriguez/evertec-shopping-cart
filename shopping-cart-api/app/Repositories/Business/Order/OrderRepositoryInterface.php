<?php namespace App\Repositories\Business\Order;

use App\Models\Business\Cart\Cart;
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

}
