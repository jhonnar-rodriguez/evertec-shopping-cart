<?php namespace App\Models\Business\Product\Attribute;

use Carbon\Carbon;

trait ProductAttribute
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
