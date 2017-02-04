<?php

namespace Project\OAuth2;

use PHPixie\AuthLogin\Providers\Password as PasswordProvider;
use Project\Api;
use Project\Model;
use Project\ORM\User\Query;

class PDO extends \OAuth2\Storage\Pdo
{

    /**
     * @var Api\Builder $builder
     */
    protected $builder;

    /**
     * PDO constructor.
     *
     * @param Api\Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;

        $pdo    = $builder->components()->database()->get()->pdo();
        $config = $builder->bundleConfig()->get('oauth', []);

//        $config = $builder->components()->orm()->query(Model::OAUTH_CLIENT)
//            ->find()
//            ->asArray(true);

        parent::__construct($pdo, $config);
    }

    /**
     * @param string $login
     * @param string $password
     *
     * @return bool
     */
    protected function checkPassword($login, $password)
    {
        $domain = $this->builder->components()->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        $user = $passwordProvider->login($login, $password);

        return $user !== null;
    }

    /**
     * @param $login
     * @param $password
     *
     * @return bool
     */
    public function checkUserCredentials($login, $password)
    {
        return $this->checkPassword($login, $password);
    }

    /**
     * @param $login
     *
     * @return array|bool
     */
    public function getUser($login)
    {
        $orm = $this->builder->components()->orm();

        /**
         * @var $queryUser Query
         */
        $queryUser = $orm->query(Model::USER);

        $user = $queryUser->findByLogin($login);

        if (!$user)
        {
            return false;
        }

        $user->user_id = $user->id();

        return (array)$user->asObject(true);
    }

    /**
     * @param      $login
     * @param      $password
     * @param null $firstName
     * @param null $lastName
     *
     * @return bool
     */
    public function setUser($login, $password, $firstName = null, $lastName = null)
    {
        $orm = $this->builder->components()->orm();

        /**
         * @var $queryUser Query
         */
        $queryUser = $orm->query(Model::USER);

        $user = $queryUser->findByLogin($login);

        $domain = $this->builder->components()->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        $passwordHash = $passwordProvider->hash($password);

        if (!$user)
        {
            $user = $orm->createEntity(Model::USER);
        }

        if ($firstName)
        {
            $user->name = $firstName;
        }

        if ($lastName)
        {
            $user->lastName = $lastName;
        }

        $user->passwordHash = $passwordHash;

        $user->save();

        return $user->id() > 0;
    }

}