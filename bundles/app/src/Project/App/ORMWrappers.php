<?php

namespace Project\App;

use PHPixie\ORM\Wrappers\Implementation as WrappersImplementation;
use Project\Model;

/**
 * ORM Wrapper registry
 */
class ORMWrappers extends WrappersImplementation
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * ORMWrappers constructor.
     *
     * @param $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @var array
     */
    protected $databaseEntities = array(
        Model::USER,
        Model::ROLE,
        Model::MENU
    );

    /**
     * @var array
     */
    protected $databaseRepositories = array(
        Model::USER
    );

    /**
     * @param $entity
     *
     * @return \Project\ORM\User\User
     */
    public function userEntity($entity)
    {
        return new \Project\ORM\User\User(
            $entity,
            $this->builder
        );
    }

    /**
     * @param $entity
     *
     * @return \Project\ORM\Role\Role
     */
    public function roleEntity($entity)
    {
        return new \Project\ORM\Role\Role(
            $entity,
            $this->builder
        );
    }

    /**
     * @param $entity
     *
     * @return \Project\ORM\Menu\Menu
     */
    public function menuEntity($entity)
    {
        return new \Project\ORM\Menu\Menu(
            $entity,
            $this->builder
        );
    }

    /**
     * @param $repository
     *
     * @return \Project\ORM\User\UserRepository
     */
    public function userRepository($repository)
    {
        return new \Project\ORM\User\UserRepository(
            $repository,
            $this->builder
        );
    }

}