<?php namespace App\Models\Business\Order;

use App\Models\Business\Order\Attribute\OrderAttribute;
use App\Models\Business\Order\Relationship\OrderRelationship;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use OrderRelationship, OrderAttribute;

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
        'user_id',
        'request_id',
        'process_url',
        'total',
        'status',
    ];

    /**
     * @param array $attributes
     */
    public function __construct( array $attributes = [] )
    {
        parent::__construct( $attributes );
        $this->table = config( 'business.core.orders.table' );
    }

}
