<?php namespace App\Models\Business\OrderItem;

use App\Models\Business\OrderItem\Relationship\OrderItemRelationship;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use OrderItemRelationship;

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
        'order_id',
        'product_id',
        'quantity',
    ];

    /**
     * @param array $attributes
     */
    public function __construct( array $attributes = [] )
    {
        parent::__construct( $attributes );
        $this->table = config( 'business.core.order_items.table' );
    }

}
