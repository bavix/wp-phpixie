<?php

namespace Project\App\ORM\User;

use PHPixie\AuthORM\Repositories\Type\Login\User as UserLogin;
use Project\App\Builder;
use Project\App\Model;
use Project\App\ORM\Role\Role;

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
        /**
         * @var $role Role
         */
        $role = $this->role();

        return $role->hasPermission($name);
    }

    /**
     * @param int $width
     *
     * @return string
     */
    public function getAvatar($width = 96)
    {
        $components = $this->builder->components();

        $http    = $components->http();
        $request = $http->request();
        $uri     = $request->uri();

        $urlPath = $uri->getScheme() . '://' . $uri->getHost() . '/svg/no-avatar.svg';

        if ($this->email)
        {
            $grAvatar = 'https://secure.gravatar.com/avatar/' . md5($this->email);

            $grAvatar .= '?s=' . $width;
            $grAvatar .= '&d=' . $urlPath;

            return $grAvatar;
        }

        return $urlPath;
    }

}