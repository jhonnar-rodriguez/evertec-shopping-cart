<?php namespace App\Http\Controllers\API\Business\Product;

use App\Http\Controllers\Controller;
use App\Models\Business\Product\Product;
use App\Repositories\Business\Product\ProductRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * ProductController constructor.
     *
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct( ProductRepositoryInterface $productRepository )
    {
        $this->productRepository = $productRepository;
    }

    /**
     * Return all the active products in the database, paginated by 10 records
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getAll( Request $request ): JsonResponse
    {
        $products = $this->productRepository->getAll( $request );

        return $this->generalResponse(
            $products['data'],
            $products['message'],
            $products['status_code']
        );
    }

    /**
     * Return all the active products in the database, paginated by 10 records
     *
     * @param Product $product
     * @param Request $request
     * @return JsonResponse
     */
    public function getProduct( Product $product, Request $request ): JsonResponse
    {
        return $this->generalResponse(
            $product,
            'Product received successfully',
            200
        );
    }

    /**
     * Return all the active products in the database, paginated by 10 records
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getProductsBySlug( Request $request ): JsonResponse
    {
        $products = $this->productRepository->getProductsBySlug( $request );

        return $this->generalResponse(
            $products['data'],
            $products['message'],
            $products['status_code']
        );
    }

}
