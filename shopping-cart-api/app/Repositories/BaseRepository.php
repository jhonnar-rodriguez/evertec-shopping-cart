<?php namespace App\Repositories;

class BaseRepository
{
    /**
     * The related model of the instantiated class
    **/
    protected $model;

    /**
     * @param $data
     * @param string $message
     * @param int $status_code
     * @return array|mixed
     */
    public function response( $data, $message, int $status_code )
    {
        return [
            'data' => $data,
            'message' => $message,
            'status_code' => $status_code,
        ];
    }

}
