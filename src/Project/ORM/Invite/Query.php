<?php

namespace Project\ORM\Invite;

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
            ->where('expires', '>', time())
            ->where('activated', 0)
            ->findOne($preload, $fields);
    }

}