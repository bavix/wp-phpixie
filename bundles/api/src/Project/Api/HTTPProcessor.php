<?php

namespace Project\Api;

class HTTPProcessor extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Constructor
     *
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

}