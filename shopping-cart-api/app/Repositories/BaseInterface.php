<?php namespace App\Repositories;

interface BaseInterface
{
    /**
     * @param array $data
     * @param string $message
     * @param int $status_code
     * @return array|mixed
     */
    public function response( $data, $message, int $status_code );

}
