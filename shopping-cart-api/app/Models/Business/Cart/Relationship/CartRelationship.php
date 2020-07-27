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

    /**
     * A cart belongs to a user
     *
     * @return mixed
     */
    public function user()
    {
        return $this->belongsTo(
            config( 'business.access.users.model' )
        );
    }

}
