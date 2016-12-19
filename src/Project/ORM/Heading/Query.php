<?php

namespace Project\ORM\Heading;

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
     * @param       $name
     * @param array $preload
     * @param null  $fields
     *
     * @return null|\PHPixie\ORM\Models\Type\Database\Implementation\Entity
     */
    public function findByTitle($name, array $preload = array(), $fields = null)
    {
        return $this->query->where('title', $name)
            ->findOne($preload, $fields);
    }

}