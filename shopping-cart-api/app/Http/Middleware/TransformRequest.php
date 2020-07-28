<?php namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TransformRequest
{
    const PARSED_METHODS = [
        'POST', 'PUT', 'PATCH'
    ];

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (in_array($request->getMethod(), self::PARSED_METHODS)) {
            $request->merge((array)json_decode($request->getContent(), true));
        }

        return $next($request);
    }
}
