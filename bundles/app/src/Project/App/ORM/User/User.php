<?php

namespace Project\App\ORM\User;

use PHPixie\AuthORM\Repositories\Type\Login\User as UserLogin;
use Project\App\Builder;
use Project\App\Model;

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
        $key = __FUNCTION__ . Model::Role . $this->roleId;

        $pool = $this->builder->cache();

        if ($pool->hasItem($key) === false || 1)
        {
            $item = $pool->getItem($key);

            $roleEntity = $this->role();

            $orm = $this->builder->components()->orm();

            $permissions = $orm->query(Model::Permission)
                ->relatedTo('roles', $roleEntity)
                ->orRelatedTo('roles', $roleEntity->children->allQuery())
                ->find()
                ->asArray(true);

            $column = array_column($permissions, 'name');
            $fillKeys = array_fill_keys($column, true);

            $item->set($fillKeys);
            $item->expiresAfter(60); // one min..

            $pool->save($item);
        }

        $permissions = $pool->getItem($key)->get();

        return isset($permissions[$name]);
    }

}