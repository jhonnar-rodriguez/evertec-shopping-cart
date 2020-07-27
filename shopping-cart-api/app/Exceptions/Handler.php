<?php namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param Throwable $exception
     * @return void
     *
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     * @param Throwable $exception
     * @return Response
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception)
    {
        if ( $exception instanceof NotFoundHttpException )
            return response()->json( [
                'success' => false,
                'message' => 'The resource you are looking for, does not exists.',
            ], 404 );

        if ( $exception instanceof MethodNotAllowedHttpException )
            return response()->json( [
                'success' => false,
                'message' => 'This route does not support the given method.',
            ], 405 );

        if ( $exception instanceof OAuthServerException )
            return response()->json( [
                'success' => false,
                'message' => $exception->getMessage(),
            ], 400 );

        if ( $exception instanceof ModelNotFoundException )
            return response()->json( [
                'success' => false,
                'message' => 'The resource you are looking for, does not exists.',
            ], 404 );

        if ( $exception instanceof AuthenticationException )
            return response()->json( [
                'success' => false,
                'message' => 'User unauthenticated or invalid token.',
            ], 401 );

        return parent::render($request, $exception);
    }
}
