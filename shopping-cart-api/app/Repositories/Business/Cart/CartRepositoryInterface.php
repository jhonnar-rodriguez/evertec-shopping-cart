<?php namespace App\Repositories\Business\Cart;

use App\Models\Business\Product\Product;
use App\Repositories\BaseInterface;
use Illuminate\Http\Request;

interface CartRepositoryInterface extends BaseInterface
{
    /**
     * Add the given product to the cart
     *
     * @param Request $request
     * @param Product $product
     * @return mixed
     */
    public function addToCart(Request $request, Product $product);

    /**
     * Remove the given product to the cart
     *
     * @param Request $request
     * @param Product $product
     * @return mixed
     */
    public function removeFromCart(Request $request, Product $product);

    /**
     * Get all the products that are in the cart for the logged user
     *
     * @param Request $request
     * @return mixed
     */
    public function getContent(Request $request);

    /**
     * Remove the items that belongs to the logged user cart
     *
     * @param Request $request
     * @return mixed
     */
    public function clearContent(Request $request);

    /**
     * Get the total amount of all the products in the cart for the logged user.
     *
     * @param Request $request
     * @return mixed
     */
    public function getTotal(Request $request);

    /**
     * @param $client_key
     * @param $user_id
     * @return mixed
     */
    public function createCartStub($client_key, $user_id = null);
}
