<?php namespace App\Repositories\Business\Product;

use App\Models\Business\Product\Product;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * UserService constructor.
     *
     * @param  Product $product
     */
    public function __construct( Product $product )
    {
        $this->model = $product;
    }

    /**
     * @inheritDoc
     */
    public function getAll( Request $request )
    {
        $products = $this->model::query()
            ->where( 'active', true )
            ->paginate(10 );

        return $this->response(
            $products,
            'Products received successfully',
            config( 'business.http_responses.success.code' )
        );
    }

}
