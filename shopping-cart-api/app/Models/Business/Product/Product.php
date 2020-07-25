<?php namespace App\Models\Business\Product;

use App\Models\Access\User\Attribute\UserAttribute;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use UserAttribute;

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
        'name',
        'slug',
        'description',
        'image',
        'price',
        'active',
    ];

    /**
     * @param array $attributes
     */
    public function __construct( array $attributes = [] )
    {
        parent::__construct( $attributes );
        $this->table = config( 'business.core.products.table' );
    }

}
