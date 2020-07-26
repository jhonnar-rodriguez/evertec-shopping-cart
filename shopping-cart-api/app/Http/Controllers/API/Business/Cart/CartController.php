<?php namespace App\Http\Controllers\API\Business\Cart;

use App\Http\Controllers\Controller;
use App\Http\Requests\Business\Product\AddProductToCartRequest;
use App\Models\Business\Product\Product;
use App\Repositories\Business\Cart\CartRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CartController extends Controller
{
    /**
     * @var CartRepositoryInterface
     */
    private $cartRepository;

    /**
     * CartController constructor.
     *
     * @param CartRepositoryInterface $cartRepository
     */
    public function __construct( CartRepositoryInterface $cartRepository )
    {
        $this->cartRepository = $cartRepository;
    }

    /**
     * Add the given product to the cart
     *
     * @param AddProductToCartRequest $request
     * @param Product $product
     * @return JsonResponse
     */
    public function addProductToCart( AddProductToCartRequest $request, Product $product ): JsonResponse
    {
        $cart = $this->cartRepository->addToCart( $request, $product );

        return $this->generalResponse(
            $cart['data'],
            $cart['message'],
            $cart['status_code']
        );
    }

    /**
     * Remove the given product from the cart
     *
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function removeProductFromCart( Request $request, Product $product ): JsonResponse
    {
        $remove = $this->cartRepository->removeFromCart( $request, $product );

        return $this->generalResponse(
            $remove['data'],
            $remove['message'],
            $remove['status_code']
        );
    }

    /**
     * Get all the products that are in the cart for the logged user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getContent( Request $request ): JsonResponse
    {
        $cartContent = $this->cartRepository->getContent( $request );

        return $this->generalResponse(
            $cartContent['data'],
            $cartContent['message'],
            $cartContent['status_code']
        );
    }

    /**
     * Remove the items that belongs to the logged user cart
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function clearCartContent( Request $request ): JsonResponse
    {
        $clearCartContent = $this->cartRepository->clearContent( $request );

        return $this->generalResponse(
            $clearCartContent['data'],
            $clearCartContent['message'],
            $clearCartContent['status_code']
        );
    }

    /**
     * Get the total amount of all the products in the cart for the logged user.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getTotal( Request $request ): JsonResponse
    {
        $cartTotal = $this->cartRepository->getTotal( $request );

        return $this->generalResponse(
            $cartTotal['data'],
            $cartTotal['message'],
            $cartTotal['status_code']
        );
    }

}
