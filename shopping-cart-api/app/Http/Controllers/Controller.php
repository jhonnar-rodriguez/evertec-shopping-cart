<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Generic response to use it in every controller so we don't need to add the response()->json in all of them
     *
     * @param $data
     * @param $message
     * @param $status_code
     * @return JsonResponse
     */
    public function generalResponse( $data, $message, $status_code ): JsonResponse
    {
        return response()->json([
            'data' => $data,
            'message' => $message,
        ], $status_code );
    }
}
