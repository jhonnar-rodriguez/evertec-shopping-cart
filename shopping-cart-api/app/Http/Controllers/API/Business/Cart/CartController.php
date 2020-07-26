<?php namespace App\Http\Controllers\API\Business\Cart;

use App\Http\Controllers\Controller;
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
     * @param Request $request
     * @param Product $product
     * @return JsonResponse
     */
    public function addProductToCart( Request $request, Product $product ): JsonResponse
    {
        $cart = $this->cartRepository->addToCart( $request, $product );

        return $this->generalResponse(
            $cart['data'],
            $cart['message'],
            $cart['status_code']
        );
    }

    public function getCartContent()
    {
        dd('Content', \Cart::getContent() );
    }

}
