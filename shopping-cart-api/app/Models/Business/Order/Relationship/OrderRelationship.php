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

}
