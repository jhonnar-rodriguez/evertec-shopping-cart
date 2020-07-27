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
     * The order has many products
     *
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany(
            config( 'business.core.order_items.model' ),
            'order_id'
        );
    }

}
