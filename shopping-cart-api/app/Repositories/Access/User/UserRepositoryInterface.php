<?php namespace App\Repositories\Access\User;

use App\Repositories\BaseInterface;
use Illuminate\Http\Request;

interface UserRepositoryInterface extends BaseInterface
{
    /**
     * Check the user credentials and grant the login returning an access token
     *
     * @param Request $request
     * @return mixed
     */
    public function login( Request $request );

    /**
     * Generate the token for the logged user
     *
     * @return array
     */
    public function generateAccessToken();

}
