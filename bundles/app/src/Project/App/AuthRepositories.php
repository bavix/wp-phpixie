<?php

namespace Project\App;

use PHPixie\Auth\Repositories\Registry\Builder;
use PHPixie\ORM;
use Project\Model;
use Project\ORM\User\UserRepository;

/**
 * Registry of user repositories for Auth component
 */
class AuthRepositories extends Builder
{

    /**
     * @var ORM
     */
    protected $orm;

    /**
     * AuthRepositories constructor.
     *
     * @param $orm ORM
     */
    public function __construct($orm)
    {
        $this->orm = $orm;
    }

    /**
     * 'user' repository
     *
     * @return UserRepository
     */
    protected function buildUserRepository()
    {
        return $this->orm->repository(Model::USER);
    }

}