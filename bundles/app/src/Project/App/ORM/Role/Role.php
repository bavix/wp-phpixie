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

    protected function cache($query, $method)
    {
        $key = $method . Model::ROLE . $this->id();

        $pool = $this->builder->cache();

        if ($pool->hasItem($key) === false)
        {
            $item = $pool->getItem($key);

            $orm = $this->builder->components()->orm();

            $permissions = $orm->query(Model::PERMISSION)
                ->relatedTo('roles', $query)
                ->find()
                ->asArray(true);

            $column   = array_column($permissions, 'name');
            $fillKeys = array_fill_keys($column, true);

            $item->set($fillKeys);
            $item->expiresAfter(60); // one min..

            $pool->save($item);
        }

        return $pool->getItem($key)->get();
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasMyPermission($name)
    {
        $permissions = $this->cache($this, __FUNCTION__);

        return isset($permissions[$name]);
    }

    /**
     * @param $name
     *
     * @return bool
     */
    public function hasPermission($name)
    {
        if ($this->hasMyPermission($name))
        {
            return true;
        }

        $query = $this->children->allQuery(); // nested set

        $permissions = $this->cache($query, __FUNCTION__);

        return isset($permissions[$name]);
    }

}