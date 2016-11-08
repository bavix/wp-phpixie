<?php

namespace Project\App;

use PHPixie\ORM\Wrappers\Implementation as WrappersImplementation;

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
     * @return ORM\User\User
     */
    public function userEntity($entity)
    {
        return new ORM\User\User(
            $entity,
            $this->builder
        );
    }

    /**
     * @param $entity
     *
     * @return ORM\Role\Role
     */
    public function roleEntity($entity)
    {
        return new ORM\Role\Role(
            $entity,
            $this->builder
        );
    }

    /**
     * @param $entity
     *
     * @return ORM\Menu\Menu
     */
    public function menuEntity($entity)
    {
        return new ORM\Menu\Menu(
            $entity,
            $this->builder
        );
    }

    /**
     * @param $repository
     *
     * @return ORM\User\UserRepository
     */
    public function userRepository($repository)
    {
        return new ORM\User\UserRepository(
            $repository,
            $this->builder
        );
    }

}