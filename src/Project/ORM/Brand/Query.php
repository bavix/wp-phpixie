<?php

namespace Project\ORM\Brand;

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
    public function findByName($name, array $preload = array(), $fields = null)
    {
        return $this->query->where('name', $name)
            ->findOne($preload, $fields);
    }

}