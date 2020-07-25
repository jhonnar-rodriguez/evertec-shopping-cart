<?php namespace App\Repositories\Access\User;

use App\Repositories\BaseRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{
    /**
     * @inheritDoc
     */
    public function login( Request $request )
    {
        try
        {
            $email = $request->email;
            $password = $request->password;
            $data = [];

            if ( auth()->attempt([ 'email' => $email, 'password' => $password ] ) )
            {
                $accessToken = $this->generateAccessToken();

                if ( $accessToken['success'] === true )
                {
                    $data   = $accessToken['data'];
                }

                $statusCode = $accessToken['code'];
                $message    = $accessToken['message'];
            }
            else
            {
                $message = config( 'business.http_responses.unauthorized.text' );
                $statusCode =  config( 'business.http_responses.unauthorized.code' );
            }
        }
        catch ( \Exception $exception )
        {
            $statusCode = config( 'business.http_responses.server_error.code' );
            $message    = 'Something went wrong trying to login the given user. Please try again.';

            Log::error(
                "UserRepository.generateAccessToken: Something went wrong trying to login the given user. " .
                "Details: {$exception->getMessage()}"
            );
        }

        return $this->response( $data, $message, $statusCode );
    }

    /**
     * @inheritDoc
     */
    public function logout( Request $request )
    {
        $request->user()->token()->revoke();

        return $this->response(
            [],
            'Successfully logged out',
            config( 'business.http_responses.success.code' )
        );
    }

    /**
     * @inheritDoc
     */
    public function generateAccessToken()
    {
        try
        {
            $accessToken = auth()
                ->user()
                ->createToken('authToken')
                ->accessToken;

            $loggedUser = auth()->user();

            $userInfo = [
                'full_name'     => $loggedUser->full_name,
                'email'         => $loggedUser->email,
                'member_since'  => $loggedUser->created_at->diffForHumans(),
            ];

            $tokenResponse = [
                'success'   => true,
                'code'      => config( 'business.http_responses.success.code' ),
                'message'   => 'User Logged In Successfully',
                'data' => [
                    'user' => $userInfo,
                    'access_token' => $accessToken
                ],
            ];
        }
        catch ( \Exception $exception )
        {
            $tokenResponse = [
                'success'   => false,
                'code'      => config( 'business.http_responses.server_error.code' ),
                'message'   => 'Something went wrong generating the token to the given user. Please try again.',
            ];

            Log::error(
                "UserRepository.generateAccessToken: Something went wrong generating the token to the given " .
                "user. Details: {$exception->getMessage()}"
            );
        }

        return $tokenResponse;
    }

}
