<?php namespace App\Repositories\Business\Product;

use App\Models\Business\Product\Product;
use App\Repositories\BaseRepository;
use Illuminate\Http\Request;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * ProductRepository constructor.
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

    /**
     * @inheritDoc
     */
    public function getProductsBySlug( Request $request )
    {
        $slugToSearch = $request->slug;

        $products = $this->model::query()
            ->where( 'active', true )
            ->where( 'slug', 'like', '%' . $slugToSearch . '%' )
            ->orWhere( 'description', 'like', '%' . $slugToSearch . '%' )
            ->paginate(10 );

        $message = 'Products received successfully';

        if ( $products->total() === 0 )
        {
            $products = [];
            $message = 'Cannot find products with the given params';
        }

        return $this->response(
            $products,
            $message,
            config( 'business.http_responses.success.code' )
        );
    }

}
