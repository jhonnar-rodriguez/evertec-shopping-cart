<?php namespace App\Helpers;

use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HttpClient
{
    /**
     * Make a request to the given received information
     *
     * @param string $url
     * @param string $method
     * @param array $data
     * @return array
     * @throws Exception
     */
    public static function makeRequest( $url, $method, $data = [] )
    {
        try
        {
            $baseUrl = config( 'environment.PLACE_TO_PAY_BASE_URL' );

            if ( empty( $baseUrl ) === true )
            {
                return [
                    'success'   => false,
                    'message'   => 'Environment variable PLACE_TO_PAY_BASE_URL cannot be empty',
                    'code'      => config( 'business.http_responses.bad_request.code' ),
                ];
            }

            $request = Http::baseUrl( $baseUrl )
                ->withoutVerifying()
                ->$method( $url, $data );

            return $request->throw()->json();
        }
        catch ( Exception $exception )
        {
            # More information about the error
            Log::error(
                "PlaceToPayService.createOrder: Something went wrong creating the order. Details: " .
                $exception->getMessage()
            );

            throw new Exception(  $exception->getMessage() );
        }
    }

}
