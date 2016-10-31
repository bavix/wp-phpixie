<?php

namespace Project\App\ORM\Role;

use \PHPixie\ORM\Wrappers\Type\Database\Entity;
use Project\App\Builder;
use Project\App\Model;

class Role extends Entity
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

    public function hasMyPermission($name)
    {
        $key = __FUNCTION__ . Model::Role . $this->id();

        $pool = $this->builder->cache();

        if ($pool->hasItem($key) === false)
        {
            $item = $pool->getItem($key);

            $orm = $this->builder->components()->orm();

            $permissions = $orm->query(Model::Permission)
                ->relatedTo('roles', $this)
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

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasPermission($name)
    {
        $key = __FUNCTION__ . Model::Role . $this->id();

        $pool = $this->builder->cache();

        if ($pool->hasItem($key) === false)
        {
            $item = $pool->getItem($key);

            $orm = $this->builder->components()->orm();

            $permissions = $orm->query(Model::Permission)
                ->relatedTo('roles', $this)
                ->orRelatedTo('roles', $this->children->allQuery())
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