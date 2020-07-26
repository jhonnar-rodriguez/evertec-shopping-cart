<?php namespace App\Models\Access\User\Relationship;

/**
 * Class UserRelationship
 */
trait UserRelationship
{
    /**
     * Return the full name of the given user
     *
     * @return string
     */
    public function cart()
    {
        return $this->hasOne(
            config( 'business.core.carts.model' )
        );
    }

}
