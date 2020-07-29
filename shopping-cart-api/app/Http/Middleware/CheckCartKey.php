<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckCartKey
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->headers->has('Cart-Key')) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'An important header is missing in your request, please check the documentation.'
            ], config('business.http_responses.bad_request.code'));
        }
    }
}
