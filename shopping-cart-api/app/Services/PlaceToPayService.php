<?php namespace App\Services;

use App\Helpers\PlaceToPay;
use stdClass;

class PlaceToPayService
{
    /**
     * @param stdClass $session_data
     * @return array[]|null
     */
    public function createOrder( stdClass $session_data )
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

        return $paymentData;
    }

}
