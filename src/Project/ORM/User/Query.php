<?php

namespace Project\ORM\User;

use Project\Framework\Builder;

class Query extends \PHPixie\ORM\Wrappers\Type\Database\Query
{

    protected $frameworkBuilder;

    /**
     * Query constructor.
     *
     * @param static  $query
     * @param Builder $builder
     */
    public function __construct($query, $builder)
    {
        parent::__construct($query);
        $this->query = $query;

        $this->frameworkBuilder = $builder;
    }

    /**
     * @param       $email
     * @param array $preload
     * @param null  $fields
     *
     * @return null|\PHPixie\ORM\Models\Type\Database\Implementation\Entity
     */
    public function findByEmail($email, array $preload = array(), $fields = null)
    {
        return $this->query->where('email', $email)
            ->findOne($preload, $fields);
    }

    /**
     * @param       $login
     * @param array $preload
     * @param null  $fields
     *
     * @return null|\PHPixie\ORM\Models\Type\Database\Implementation\Entity
     */
    public function findByLogin($login, array $preload = array(), $fields = null)
    {
        return $this->query->where('login', $login)
            ->findOne($preload, $fields);
    }

}