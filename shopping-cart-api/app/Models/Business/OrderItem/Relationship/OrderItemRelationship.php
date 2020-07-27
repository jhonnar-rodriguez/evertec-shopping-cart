<?php namespace App\Models\Business\OrderItem\Relationship;


trait OrderItemRelationship
{
    /**
     * The order belongs to a user
     *
     * @return mixed
     */
    public function order()
    {
        return $this->belongsTo(
            config( 'business.core.orders.model' )
        );
    }

}
