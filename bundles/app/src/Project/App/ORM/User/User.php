<?php

namespace Project\App\ORM\User;

use PHPixie\AuthORM\Repositories\Type\Login\User as UserLogin;
use Project\App\Builder;
use Project\App\Model;
use Project\App\ORM\Role\Role;

/**
 * User entity with support for Login auth
 */
class User extends UserLogin
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    public function __construct($entity, $builder)
    {
        parent::__construct($entity);
        $this->builder = $builder;
    }

    /**
     * @return string
     */
    public function passwordHash()
    {
        return $this->passwordHash;
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasPermission($name)
    {
        /**
         * @var $role Role
         */
        $role = $this->role();

        return $role->hasPermission($name);
    }

}