<?php namespace App\Repositories\Business\Cart;

use App\Models\Business\Cart\Cart;
use App\Models\Business\CartItem\CartItem;
use App\Models\Business\Product\Product;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var $loggedUser
     */
    private $loggedUser;

    /**
     * CartRepository constructor.
     *
     * @param Cart $cart
     */
    public function __construct( Cart $cart )
    {
        $this->cart = $cart;
    }

    /**
     * @inheritDoc
     **/
    public function addToCart( Request $request, Product $product )
    {
        try
        {
            $productQuantity = ( int ) $request->quantity;

            # Getting the cart and the items for the logged user
            $this->loggedUser = $request->user();
            $userCart   = $this->handleUserCart();
            $cartItems  = $userCart->items();
            $productID  = $product->id;

            $productInCart = $cartItems->where( 'product_id', $productID )->first();

            if ( empty( $productInCart ) == false )
            {
                $cartItems
                    ->where( 'product_id', $productID )
                    ->update([
                        'quantity' => $productQuantity,
                    ]);
            }
            else
            {
                $cartItems->save(
                    new CartItem(
                        [
                            'cart_id'       => $userCart->id,
                            'product_id'    => $productID,
                            'quantity'      => $productQuantity,
                        ]
                    )
                );
            }

            return $this->response(
                [],
                "Item successfully added to the cart",
                config( 'business.http_responses.created.code' )
            );
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "CartRepository.addToCart: Something went wrong adding the given product to the cart. " .
                "Details: {$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong adding the given product to the cart, please try again later.',
                config( 'business.http_responses.server_error.code' )
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function getContent( Request $request )
    {
        try
        {
            # Getting the cart and the items for the logged user
            $loggedUserCart = $request->user()->cart;
            $totalProducts = [];
            $message = "Cart received successfully";
            $statusCode = config( 'business.http_responses.success.code' );

            if ( empty( $loggedUserCart ) === true )
            {
                $message = "You need to add products to your shopping cart first. Please, try again.";
                $statusCode = config( 'business.http_responses.bad_request.code' );
            }
            else
            {
                foreach ( $loggedUserCart->items as $item )
                {
                    $product = $item->product;

                    if ( empty( $product ) === true )
                    {
                        continue;
                    }

                    $newProduct = [
                        'id'        => $product->id,
                        'name'      => $product->name,
                        'price'     => $product->price,
                        'quantity'  => $item->quantity,
                        'image'     => $product->image,
                    ];

                    array_push( $totalProducts, $newProduct );
                }
            }

            return $this->response(
                $totalProducts,
                $message,
                $statusCode
            );
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "CartRepository.getContent: Something went wrong getting the cart content. Details: " .
                "{$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong getting the cart content, please try again later.',
                config( 'business.http_responses.server_error.code' )
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function removeFromCart( Request $request, Product $product )
    {
        try
        {
            # Getting the cart and the items for the logged user
            $loggedUserCart = $request->user()->cart;
            $message = "Item successfully removed from the cart";
            $statusCode = config( 'business.http_responses.bad_request.code' );

            if ( empty( $loggedUserCart ) === true )
            {
                $message = "You need to add products to your shopping cart first. Please, try again.";
            }
            else
            {
                # First we need to check if the product exists in the user shopping cart
                $getProductQuery = $loggedUserCart->items()->where( 'product_id', $product->id );
                $productInCart = $getProductQuery->first();

                if ( empty( $productInCart ) === true )
                {
                    $message = "The product does not exists in your shopping cart. Please, try again.";
                }
                else
                {
                    # Remove the product
                    $getProductQuery->delete();
                    $statusCode = config( 'business.http_responses.success.code' );
                }
            }

            return $this->response(
                [],
                $message,
                $statusCode
            );
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "CartRepository.removeFromCart: Something went wrong removing the product from the " .
                "cart Details: {$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong removing the product from the cart, please try again later.',
                config( 'business.http_responses.server_error.code' )
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function clearContent( Request $request )
    {
        try
        {
            # Getting the cart and the items for the logged user
            $loggedUserCart = $request->user()->cart;
            $message = "Cart cleared successfully";
            $statusCode = config( 'business.http_responses.success.code' );

            if ( empty( $loggedUserCart ) === true )
            {
                $message = "You need to add products to your shopping cart first. Please, try again.";
                $statusCode = config( 'business.http_responses.bad_request.code' );
            }
            else
            {
                $loggedUserCart->items()->delete();
            }

            return $this->response(
                [],
                $message,
                $statusCode
            );
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "CartRepository.clearContent: Something went wrong clearing the cart content. Details: " .
                "{$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong clearing the cart content, please try again later.',
                config( 'business.http_responses.server_error.code' )
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function getTotal( Request $request )
    {
        try
        {
            # Getting the cart and the items for the logged user
            $loggedUserCart = $request->user()->cart;
            $total = $loggedUserCart
                ->items
                ->reduce( function ( $total, CartItem $cartItem )
                {
                    $product = $cartItem->product;
                    return $total + ( $cartItem->quantity * $product->price );
                }, 0 );

            return $this->response(
                [
                    'total' => round( $total, 2 ),
                ],
                "Cart total received successfully",
                config( 'business.http_responses.success.code' )
            );
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "CartRepository.getTotal: Something went wrong getting the cart total. Details: " .
                "{$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong getting the cart total, please try again later.',
                config( 'business.http_responses.server_error.code' )
            );
        }
    }

    /**
     * Return the user cart based on the logged user.
     * If the user does not have a cart it will create a new one
     * If already has a cart the we will return the same cart
     *
     * @return mixed|null
     */
    private function handleUserCart()
    {
        $loggedUser = $this->loggedUser;
        $userCart = isset( $loggedUser->cart ) ? $loggedUser->cart : null;

        if ( is_null( $userCart ) === false )
        {
            return $userCart;
        }
        else
        {
            if ( $this->createCartStub() === true )
            {
                # We need to get the relation value again
                $userCart = $this->loggedUser->cart()->first();
            }
            else
            {
                $userCart = null;
            }
        }

        return $userCart;
    }

    /**
     * Create a new cart to the logged user
     *
     * @return bool
     */
    private function createCartStub()
    {
        try
        {
            $this->loggedUser->cart()
                ->create(
                    [
                        'created_at' => Carbon::now()
                    ]
                );

            return true;
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "CartRepository.createCartStub: Something went wrong adding a new cart to the user. Details: " .
                "{$exception->getMessage()}"
            );

            return false;
        }
    }
}
