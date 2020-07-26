<?php namespace App\Models\Business\Cart;

use App\Models\Business\Cart\Relationship\CartRelationship;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use CartRelationship;

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
    ];

    /**
     * @param array $attributes
     */
    public function __construct( array $attributes = [] )
    {
        parent::__construct( $attributes );
        $this->table = config( 'business.core.carts.table' );
    }

}
