<?php namespace App\Models\Business\Order\Attribute;

use Carbon\Carbon;

/**
 * Class OrderAttribute
 */
trait OrderAttribute
{
    /**
     * Return the created at value readable for humans
     *
     * @param $value
     * @return mixed
     */
    public function getCreatedAtAttribute( $value )
    {
        return Carbon::parse( $value )->diffForHumans();
    }

    /**
     * Return the updated at value readable for humans
     *
     * @param $value
     * @return mixed
     */
    public function getUpdatedAtAttribute( $value )
    {
        return Carbon::parse( $value )->diffForHumans();
    }

}
