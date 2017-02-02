<?php

namespace Project\Cp;

use PHPixie\HTTP\Request;
use Project\Extension\Util;

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
     * @return HTTPProcessors\Dashboard
     */
    protected function buildDashboardProcessor()
    {
        return new HTTPProcessors\Dashboard($this->builder);
    }

    /**
     * @return HTTPProcessors\Upload
     */
    protected function buildUploadProcessor()
    {
        return new HTTPProcessors\Upload($this->builder);
    }

    /**
     * @return HTTPProcessors\Auth
     */
    protected function buildAuthProcessor()
    {
        return new HTTPProcessors\Auth($this->builder);
    }

    /**
     * @return HTTPProcessors\SettingsProcessorBuilder
     */
    protected function buildSettingsProcessor()
    {
        return new HTTPProcessors\SettingsProcessorBuilder($this->builder);
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