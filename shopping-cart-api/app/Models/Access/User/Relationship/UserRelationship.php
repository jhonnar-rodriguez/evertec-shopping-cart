<?php namespace App\Models\Access\User\Relationship;

/**
 * Class UserRelationship
 */
trait UserRelationship
{
    /**
     * An user has one cart
     *
     * @return string
     */
    public function cart()
    {
        return $this->hasOne(
            config( 'business.core.carts.model' )
        );
    }

    /**
     * An user has many orders
     *
     * @return string
     */
    public function orders()
    {
        return $this->hasMany(
            config( 'business.core.orders.model' )
        );
    }


}
