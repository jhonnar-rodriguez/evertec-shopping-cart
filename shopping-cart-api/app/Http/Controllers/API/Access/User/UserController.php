<?php namespace App\Http\Controllers\API\Access\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Access\User\UserLoginRequest;
use App\Repositories\Access\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;

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

}
