<?php

namespace Project\Api;

use PHPixie\DefaultBundle\Processor\HTTP\Builder as HttpBuilder;
use PHPixie\HTTP\Request;
use Project\Extension\Util;

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
     * @return HTTPProcessors\Auth
     */
    public function buildAuthProcessor()
    {
        return new HTTPProcessors\Auth($this->builder);
    }

    /**
     * @param Request $httpRequest
     *
     * @return string
     */
    protected function getProcessorNameFor($httpRequest)
    {
        $processorName = $httpRequest->attributes()->get($this->attributeName);
        $processorName = Util::camelCase($processorName);

        return $processorName;
    }

    /**
     * Build 'admin' processor group
     *
     * @return HTTPProcessors\SOWProcessorBuilder
     */
    protected function buildSowProcessor()
    {
        return new HTTPProcessors\SOWProcessorBuilder($this->builder);
    }

    /**
     * Build 'admin' processor group
     *
     * @return HTTPProcessors\SOCProcessorBuilder
     */
    protected function buildSocProcessor()
    {
        return new HTTPProcessors\SOCProcessorBuilder($this->builder);
    }

    /**
     * Build 'admin' processor group
     *
     * @return HTTPProcessors\SOUProcessorBuilder
     */
    protected function buildSouProcessor()
    {
        return new HTTPProcessors\SOUProcessorBuilder($this->builder);
    }

}