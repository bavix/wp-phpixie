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
        Model::User
    );

    /**
     * @var array
     */
    protected $databaseRepositories = array(
        Model::User
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