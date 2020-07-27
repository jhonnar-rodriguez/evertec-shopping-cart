<?php namespace App\Services;

use App\Helpers\HttpClient;
use App\Helpers\PlaceToPay;
use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;

class PlaceToPayService
{
    /**
     * @param stdClass $session_data
     * @return array[]|null
     * @throws Exception
     */
    public function createOrder( stdClass $session_data )
    {
        try
        {
            $order = $session_data->order;

            $paymentAdditionalInfo = PlaceToPay::preparePaymentAdditionalInfo(
                $session_data->frontend_url,
                $order->id,
                $order->total,
                $session_data->request_ip,
                $session_data->user_agent
            );

            # Prepare the data to send the information to PlaceToPay
            $paymentData = PlaceToPay::prepareRequestData(
                $session_data->cart,
                $paymentAdditionalInfo
            );

            # Make the request to the PlaceToPay service
            $requestData    = HttpClient::makeRequest( '/api/session', 'POST', $paymentData );
            $requestStatus  = isset( $requestData['status']['status'] ) ? $requestData['status']['status'] : null;

            if ( $requestStatus === "OK" )
            {
                $orderData = [
                    'success'       => true,
                    'message'       => isset( $requestData['status']['message'] ) ? $requestData['status']['message'] : null,
                    'request_id'    => isset( $requestData['requestId'] ) ? $requestData['requestId'] : null,
                    'process_url'   => isset( $requestData['processUrl'] ) ? $requestData['processUrl'] : null,
                    'code'          => config( 'business.http_responses.success.code' ),
                ];
            }
            else
            {
                $message = "Unexpected output from service, please try again later.";

                if ( isset( $requestData['status']['message'] ) === true )
                {
                    $message = $requestData['status']['message'];
                }
                elseif ( isset( $requestData['message'] ) === true )
                {
                    $message = $requestData['message'];
                }

                $orderData = [
                    'success'   => false,
                    'message'   => $message,
                    'code'      => config( 'business.http_responses.bad_request.code' ),
                ];
            }
        }
        catch ( Exception $exception )
        {
            # More information about the error
            Log::error(
                "PlaceToPayService.createOrder: Something went wrong creating the order. Details: " .
                $exception->getMessage()
            );

            throw new Exception( $exception->getMessage() );
        }

        return $orderData;
    }

}
