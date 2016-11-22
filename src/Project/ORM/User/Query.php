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

    public function isActive

}