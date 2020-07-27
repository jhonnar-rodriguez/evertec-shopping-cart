<?php namespace App\Helpers;

use App\Models\Access\User\User;
use App\Models\Business\Cart\Cart;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Log;
use stdClass;

class PlaceToPay
{
    /**
     * Prepare the whole data to send it to the createRequest service of PlaceToPay
     *
     * @param Cart $cart
     * @param stdClass $payment_additional_info
     * @return array[]|null
     */
    public static function prepareRequestData( Cart $cart, stdClass $payment_additional_info )
    {
        try
        {
            $buyerUser = $cart->user;
            $authData = static::prepareAuthData();
            $buyerData = static::prepareBuyerData( $buyerUser );
            $paymentData = static::preparePaymentData(
                $payment_additional_info->reference,
                $buyerUser->full_name,
                $payment_additional_info->total
            );

            $expirationDate = Carbon::now()->addMinutes( 30 )->toIso8601String();

            return [
                'auth' => $authData,
                'buyer' => $buyerData,
                'payment' => $paymentData,
                'locale' => 'en_CO',
                'expiration' => $expirationDate,
                'returnUrl' => $payment_additional_info->return_url,
                'ipAddress' => $payment_additional_info->ip,
                'userAgent' => $payment_additional_info->user_agent,
            ];
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "PlaceToPayAuth.formatAuthData: Something went wrong formatting the data. Details: " .
                "{$exception->getMessage()}"
            );

            return null;
        }
    }

    /**
     * Create a new object with additional information about the transaction
     *
     * @param string $frontend_url
     * @param int $order_id
     * @param float $order_total
     * @param $ip
     * @param $user_argent
     * @return stdClass
     */
    public static function preparePaymentAdditionalInfo( $frontend_url, $order_id, $order_total, $ip, $user_argent )
    {
        $returnUrl = "$frontend_url/$order_id";
        $additionalInfo = new stdClass();
        $additionalInfo->total = $order_total;
        $additionalInfo->reference = $order_id;
        $additionalInfo->return_url = $returnUrl;
        $additionalInfo->ip = $ip;
        $additionalInfo->user_agent = $user_argent;

        return $additionalInfo;
    }

    /**
     * Prepare the auth data structure
     *
     * @return array[]|null
     */
    public static function prepareAuthData()
    {
        try
        {
            $authSeed = static::generateSeed();
            $authNonce = static::generateNonce();
            $authTranKey = static::generateTranKey( $authNonce, $authSeed );

            return [
                'login' => config( 'environment.PLACE_TO_PAY_LOGIN' ),
                'seed' => $authSeed,
                'nonce' => base64_encode( $authNonce ),
                'tranKey' => $authTranKey,
            ];
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "PlaceToPayAuth.formatAuthData: Something went wrong setting up the auth data. Details: " .
                "{$exception->getMessage()}"
            );

            return null;
        }
    }

    /**
     * Prepare the buyer data structure
     *
     * @param User $user
     * @return array[]|null
     */
    private static function prepareBuyerData( User $user )
    {
        try
        {
            $dni = mt_rand( 100000000, 999999999 );
            $dniType = 'CC';

            return [
                'name' => $user->first_name,
                'surname' => $user->last_name,
                'email' => $user->email,
                'document' => $dni,
                'documentType' => $dniType,
                'mobile' => $user->phone_number,
            ];
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "PlaceToPayAuth.prepareBuyerData: Something went wrong setting up the buyer data. Details: " .
                "{$exception->getMessage()}"
            );

            return null;
        }
    }

    /**
     * Prepare the payment data structure
     *
     * @param $reference
     * @param $user_name
     * @param $total
     * @return array[]|null
     */
    private static function preparePaymentData( $reference, $user_name, $total )
    {
        try
        {
            return  [
                'reference' => $reference,
                'description' => "Basic payment for $user_name",
                'amount' => [
                    'currency' => 'COP',
                    'total' => $total,
                ],
            ];
        }
        catch ( \Exception $exception )
        {
            Log::error(
                "PlaceToPayAuth.preparePaymentData: Something went wrong setting up the payment data. " .
                "Details: {$exception->getMessage()}"
            );

            return null;
        }
    }

    /**
     * Get the current date in ISO 8601
     *
     * @return false|string
     */
    private static function generateSeed()
    {
        return date('c' );
    }

    /**
     * Random value for each request encoded in base64
     *
     * @return string|null
     * @throws Exception
     */
    private static function generateNonce()
    {
        try
        {
            if ( function_exists('random_bytes') )
            {
                $nonce = bin2hex( random_bytes(16 ) );
            }
            elseif ( function_exists('openssl_random_pseudo_bytes' ) )
            {
                $nonce = bin2hex( openssl_random_pseudo_bytes( 16 ) );
            }
            else
            {
                $nonce = mt_rand();
            }

            return $nonce;
        }
        catch ( Exception $exception )
        {
            Log::error(
                "PlaceToPayAuth.generateNonce: Something went wrong generating the nonce. Details: " .
                "{$exception->getMessage()} "
            );

            return null;
        }
    }

    /**
     * Generate a unique transaction key to identify the operation
     *
     * @param $nonce
     * @param $seed
     * @return string
     */
    private static function generateTranKey( $nonce, $seed )
    {
        $secretKey = config( 'environment.PLACE_TO_PAY_SECRET_KEY' );

        return  base64_encode(
            sha1(
                $nonce . $seed . $secretKey,
                true
            )
        );
    }

}
