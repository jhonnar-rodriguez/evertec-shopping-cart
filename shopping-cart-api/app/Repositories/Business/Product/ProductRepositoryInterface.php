<?php namespace App\Repositories\Business\Product;

use App\Repositories\BaseInterface;
use Illuminate\Http\Request;

interface ProductRepositoryInterface extends BaseInterface
{
    /**
     * Get all the products in the table
     *
     * @param Request $request
     * @return mixed
     */
    public function getAll( Request $request );

}
