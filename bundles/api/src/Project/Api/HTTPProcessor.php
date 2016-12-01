<?php

namespace Project\Api;

use PHPixie\DefaultBundle\Processor\HTTP\Builder as HttpBuilder;

class HTTPProcessor extends HttpBuilder
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

    /**
     * @return HTTPProcessors\AuthProcessor
     */
    public function buildAuthProcessor()
    {
        return new HTTPProcessors\Auth($this->builder);
    }

    /**
     * @return HTTPProcessors\AuthProcessor
     */
    public function buildBrandProcessor()
    {
        return new HTTPProcessors\Brand($this->builder);
    }

}