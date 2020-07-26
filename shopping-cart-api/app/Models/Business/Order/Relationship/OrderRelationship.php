<?php namespace App\Models\Business\Order\Relationship;


trait OrderRelationship
{
    /**
     * The order belongs to a user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(
            config( 'business.access.users.table' )
        );
    }

    /**
     * The order has one cart
     *
     * @return mixed
     */
    public function cart()
    {
        return $this->hasOne(
            config( 'business.core.carts.table' )
        );
    }

}
