<?php

namespace Project\ORM\User;

use PHPixie\AuthORM\Repositories\Type\Login;
use PHPixie\AuthSocial\Repository as AuthSocialRepository;
use Project\App\Builder;

/**
 * User repository with support for Login auth
 */
class UserRepository extends Login implements AuthSocialRepository
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    public function __construct($repository, $builder)
    {
        parent::__construct($repository);
        $this->builder = $builder;
    }

    /**
     * @param $socialUser
     *
     * @return mixed
     */
    public function getBySocialUser($socialUser)
    {
        /**
         * @var $providerName string
         */
        $providerName = $socialUser->providerName();

        /**
         * @var $field string
         */
        $field = $this->socialIdField($providerName);

        /**
         * @var $userQuery \PHPixie\ORM\Models\Type\Database\Query
         */
        $userQuery = $this->query();
        $userQuery->where($field, $socialUser->id());

        return $userQuery->findOne();
    }

    /**
     * @param $providerName string
     *
     * @return string
     */
    public function socialIdField($providerName)
    {
        return $providerName . 'Id';
    }

    /**
     * @return array Array of fields that can be used to login with
     */
    protected function loginFields()
    {
        return array('login', 'email');
    }

}