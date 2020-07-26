<?php namespace App\Models\Business\CartItem;

use App\Models\Business\CartItem\Relationship\CartItemRelationship;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use CartItemRelationship;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'cart_id',
        'product_id',
        'quantity',
    ];

    /**
     * @param array $attributes
     */
    public function __construct( array $attributes = [] )
    {
        parent::__construct( $attributes );
        $this->table = config( 'business.core.cart_items.table' );
    }

}
