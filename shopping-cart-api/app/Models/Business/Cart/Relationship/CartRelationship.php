<?php namespace App\Models\Business\Cart\Relationship;


trait CartRelationship
{
    /**
     * A cart has many items
     *
     * @return mixed
     */
    public function items()
    {
        return $this->hasMany(
            config( 'business.core.cart_items.model' ),
            'cart_id'
        );
    }

}
