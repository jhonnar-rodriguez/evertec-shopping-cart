<?php namespace App\Models\Business\CartItem\Relationship;

trait CartItemRelationship
{
    /**
     * The items belongs to a cart
     *
     * @return mixed
     */
    public $incrementing = false;

    public function cart()
    {
        return $this->belongsTo(
            config( 'business.core.carts.model' )
        );
    }

    /**
     * An item has a product related
     *
     * @return mixed
     */
    public function product()
    {
        return $this->hasOne(
            config( 'business.core.products.model' )
        );
    }

}
