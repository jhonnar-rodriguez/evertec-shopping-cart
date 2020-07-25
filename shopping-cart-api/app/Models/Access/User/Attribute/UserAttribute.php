<?php namespace App\Models\Access\User\Attribute;

/**
 * Class UserAttribute
 */
trait UserAttribute
{
    /**
     * Return the full name of the given user
     *
     * @return string
     */
    public function getFullNameAttribute() : string
    {
        return  ucwords( strtolower( "{$this->first_name} {$this->last_name}" ) );
    }

}
