<?php namespace App\Repositories\Business\Cart;

use App\Models\Business\Cart\Cart;
use App\Models\Business\CartItem\CartItem;
use App\Models\Business\Product\Product;
use App\Repositories\BaseRepository;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CartRepository extends BaseRepository implements CartRepositoryInterface
{
    /**
     * @var Cart
     */
    private $cart;

    /**
     * @var
     */
    private $loggedUser = null;

    /**
     * CartRepository constructor.
     *
     * @param Cart $cart
     */
    public function __construct(Cart $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @inheritDoc
     **/
    public function addToCart(Request $request, Product $product)
    {
        DB::beginTransaction();
        try {
            $productQuantity = ( int ) $request->quantity;

            # Getting the cart and the items for the logged user
            $this->assignLoggedUser();

            # Generate a new cart with the given product or return the previous one
            $userCart = $this->handleUserCart($request->client_key);
            $cartItems = $userCart->items();
            $productID = $product->id;

            $productInCart = $cartItems->where('product_id', $productID)->first();

            if (empty($productInCart) == false) {
                $cartItems
                    ->where('product_id', $productID)
                    ->update([
                        'quantity' => $productQuantity,
                    ]);
            } else {
                $cartItems->save(
                    new CartItem(
                        [
                            'cart_id' => $userCart->id,
                            'product_id' => $productID,
                            'quantity' => $productQuantity,
                        ]
                    )
                );
            }

            DB::commit();

            return $this->response(
                [
                    'total' => $userCart->items()->count()
                ],
                "Item successfully added to the cart",
                config('business.http_responses.created.code')
            );
        } catch (Exception $exception) {
            DB::rollBack();

            Log::error(
                "CartRepository.addToCart: Something went wrong adding the given product to the cart. " .
                "Details: {$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong adding the given product to the cart, please try again later.',
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function getContent(Request $request)
    {
        try {
            $this->assignLoggedUser();

            # Generate a new cart with the given product or return the previous one
            $loggedUserCart = $this->handleUserCart($request->client_key, false);

            if (empty($loggedUserCart) === true) {
                return $this->response(
                    [],
                    "The cart is empty",
                    config('business.http_responses.success.code')
                );
            }

            $totalProducts = [];
            $message = "Cart received successfully";
            $statusCode = config('business.http_responses.success.code');

            if (empty($loggedUserCart) === true) {
                $message = "You need to add products to your shopping cart first. Please, try again.";
            } else {
                $cartTotal = $loggedUserCart->items
                    ->reduce(function ($total, CartItem $cartItem) {
                        $product = $cartItem->product;

                        return $total + ($cartItem->quantity * $product->price);
                    }, 0);

                foreach ($loggedUserCart->items as $item) {
                    $product = $item->product;

                    if (empty($product) === true) {
                        continue;
                    }

                    $newProduct = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => $product->image,
                        'quantity' => $item->quantity,
                        'description' => $product->description,
                    ];

                    array_push($totalProducts, $newProduct);
                }
            }

            return $this->response(
                $totalProducts,
                $message,
                $statusCode
            );
        } catch (Exception $exception) {
            Log::error(
                "CartRepository.getContent: Something went wrong getting the cart content. Details: " .
                "{$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong getting the cart content, please try again later.',
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function removeFromCart(Request $request, Product $product)
    {
        try {
            # Getting the cart and the items for the logged user
            $loggedUserCart = $request->user()->cart;
            $message = "Item successfully removed from the cart";
            $statusCode = config('business.http_responses.bad_request.code');

            if (empty($loggedUserCart) === true) {
                $message = "You need to add products to your shopping cart first. Please, try again.";
            } else {
                # First we need to check if the product exists in the user shopping cart
                $getProductQuery = $loggedUserCart->items()->where('product_id', $product->id);
                $productInCart = $getProductQuery->first();

                if (empty($productInCart) === true) {
                    $message = "The product does not exists in your shopping cart. Please, try again.";
                } else {
                    # Remove the product
                    $getProductQuery->delete();
                    $statusCode = config('business.http_responses.success.code');
                }
            }

            return $this->response(
                [],
                $message,
                $statusCode
            );
        } catch (Exception $exception) {
            Log::error(
                "CartRepository.removeFromCart: Something went wrong removing the product from the " .
                "cart Details: {$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong removing the product from the cart, please try again later.',
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function clearContent(Request $request)
    {
        try {
            # Getting the cart and the items for the logged user
            $loggedUserCart = $request->user()->cart;
            $message = "Cart cleared successfully";
            $statusCode = config('business.http_responses.success.code');

            if (empty($loggedUserCart) === true) {
                $message = "You need to add products to your shopping cart first. Please, try again.";
                $statusCode = config('business.http_responses.bad_request.code');
            } else {
                $loggedUserCart->items()->delete();
            }

            return $this->response(
                [],
                $message,
                $statusCode
            );
        } catch (Exception $exception) {
            Log::error(
                "CartRepository.clearContent: Something went wrong clearing the cart content. Details: " .
                "{$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong clearing the cart content, please try again later.',
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * @inheritDoc
     **/
    public function getTotal(Request $request)
    {
        try {
            # Getting the cart and the items for the logged user
            $loggedUserCart = $this->handleUserCart($request->client_key, false);

            if (empty($loggedUserCart) === true) {
                return $this->response(
                    [],
                    "The cart is empty",
                    config('business.http_responses.success.code')
                );
            }

            $total = $loggedUserCart
                ->items
                ->reduce(function ($total, CartItem $cartItem) {
                    $product = $cartItem->product;

                    return $total + ($cartItem->quantity * $product->price);
                }, 0);

            return $this->response(
                [
                    'total' => round($total, 2),
                ],
                "Cart total received successfully",
                config('business.http_responses.success.code')
            );
        } catch (Exception $exception) {
            Log::error(
                "CartRepository.getTotal: Something went wrong getting the cart total. Details: " .
                "{$exception->getMessage()}"
            );

            return $this->response(
                [],
                'Something went wrong getting the cart total, please try again later.',
                config('business.http_responses.server_error.code')
            );
        }
    }

    /**
     * Return the user cart based on the logged user.
     * If the user does not have a cart it will create a new one
     * If already has a cart the we will return the same cart
     *
     * @param string $client_key Unique client id generated in the client
     * @param bool $create_if_not_exists
     * @return mixed|null
     */
    private function handleUserCart($client_key, $create_if_not_exists = true)
    {
        $loggedUser = $this->loggedUser;
        $userCart   = null;

        if (empty($loggedUser) === false) {
            $userCart = isset($loggedUser->cart) ? $loggedUser->cart : null;
        }

        # The user could be logged but that does not mean they have a created cart
        # That's why we need to add another validation getting a cart using the client id
        if (is_null($userCart) === true) {
            $userCart = $this->getCartByKey($client_key);
        }

        # If at the end the user does not have a shopping cart we need to create a new one
        if (is_null($userCart) === false) {
            if (isset($loggedUser->id) === true && ($userCart->user_id !== $loggedUser->id)) {
                $userCart->user_id = $loggedUser->id;
                $userCart->save();
            }

            return $userCart;
        } elseif ($create_if_not_exists === true) {
            $userCart = $this->createCartStub($client_key);
        }

        return $userCart;
    }

    /**
     * Create a new cart to the logged user
     *
     * @param string $client_key Unique client id generated in the client
     * @return Cart|null
     */
    private function createCartStub($client_key)
    {
        try {
            $newCart = new $this->cart();
            $newCart->key = $client_key;

            if (isset($this->loggedUser->id)) {
                $newCart->user_id = $this->loggedUser->id;
            }

            if ($newCart->save() === false) {
                $newCart = null;
            }

            return $newCart;
        } catch (Exception $exception) {
            Log::error(
                "CartRepository.createCartStub: Something went wrong adding a new cart to the user. Details: " .
                "{$exception->getMessage()}"
            );

            return null;
        }
    }

    /**
     * @param $client_key
     * @return Builder|Model|object|null
     */
    private function getCartByKey($client_key)
    {
        return $this->cart->query()->where('key', $client_key)->first();
    }

    /**
     * Assign the logged user information if there is any logged user in the application
     */
    private function assignLoggedUser()
    {
        if (Auth::guard('api')->check()) {
            $this->loggedUser = auth('api')->user();
        }
    }
}
