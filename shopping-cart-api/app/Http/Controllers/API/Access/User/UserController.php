<?php namespace App\Http\Controllers\API\Access\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Access\User\UserLoginRequest;
use App\Repositories\Access\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    /**
     * UserController constructor.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct( UserRepositoryInterface $userRepository )
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Authenticate and generate a new token if the credentials are valid
     *
     * @param UserLoginRequest $request
     * @return JsonResponse
     */
    public function login( UserLoginRequest $request ): JsonResponse
    {
        $login = $this->userRepository->login( $request );

        return $this->generalResponse(
            $login['data'],
            $login['message'],
            $login['status_code']
        );
    }

    /**
     * Revoke the token to the logged user
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function logout( Request $request ): JsonResponse
    {
        $logout = $this->userRepository->logout( $request );

        return $this->generalResponse(
            $logout['data'],
            $logout['message'],
            $logout['status_code']
        );
    }

}
