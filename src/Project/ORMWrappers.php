<?php

namespace Project;

use PHPixie\ORM\Wrappers\Implementation as WrappersImplementation;

/**
 * ORM Wrapper registry
 */
class ORMWrappers extends WrappersImplementation
{

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
    protected $databaseQueries = array(
        Model::INVITE,
        Model::USER,
        Model::BRAND
    );

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
     * @param $query
     *
     * @return ORM\Invite\Query
     */
    public function inviteQuery($query)
    {
        return new ORM\Invite\Query($query, $this->builder);
    }

    /**
     * @param $query
     *
     * @return ORM\User\Query
     */
    public function userQuery($query)
    {
        return new ORM\User\Query($query, $this->builder);
    }

    /**
     * @param $query
     *
     * @return ORM\Brand\Query
     */
    public function brandQuery($query)
    {
        return new ORM\Brand\Query($query, $this->builder);
    }

    /**
     * @param $entity
     *
     * @return ORM\User\User
     */
    public function userEntity($entity)
    {
        return new ORM\User\User($entity, $this->builder);
    }

    /**
     * @param $entity
     *
     * @return ORM\Role\Role
     */
    public function roleEntity($entity)
    {
        return new ORM\Role\Role($entity, $this->builder);
    }

    /**
     * @param $entity
     *
     * @return ORM\Menu\Menu
     */
    public function menuEntity($entity)
    {
        return new ORM\Menu\Menu($entity, $this->builder);
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