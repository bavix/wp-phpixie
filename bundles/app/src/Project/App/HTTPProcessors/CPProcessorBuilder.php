<?php

namespace Project\App\HTTPProcessors;

use Project\App\Builder;
use Project\Util;

/**
 * Builds processors in the 'app.admin' namespace
 */
class CPProcessorBuilder extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Specifies which request attribute will be used
     * to select a processor.
     *
     * @var string
     */
    protected $attributeName = 'cpProcessor';

    /**
     * Constructor
     *
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    protected function getProcessorNameFor($httpRequest)
    {
        $processorName = $httpRequest->attributes()->get($this->attributeName);
        $processorName = Util::camelCase($processorName);

        return $processorName;
    }

    /**
     * @return CP\Dashboard
     */
    protected function buildDashboardProcessor()
    {
        return new CP\Dashboard($this->builder);
    }

    /**
     * @return CP\Auth
     */
    protected function buildAuthProcessor()
    {
        return new CP\Auth($this->builder);
    }

    /**
     * @return CP\Settings
     */
    protected function buildSettingsProcessor()
    {
        return new CP\Settings($this->builder);
    }

    /**
     * @return CP\Upload
     */
    protected function buildUploadProcessor()
    {
        return new CP\Upload($this->builder);
    }

    /**
     * @return CP\Role
     */
    protected function buildRoleProcessor()
    {
        return new CP\Role($this->builder);
    }

    /**
     * @return CP\User
     */
    protected function buildUserProcessor()
    {
        return new CP\User($this->builder);
    }

    /**
     * Build 'admin' processor group
     *
     * @return CP\SOWProcessorBuilder
     */
    protected function buildSowProcessor()
    {
        return new CP\SOWProcessorBuilder($this->builder);
    }

    /**
     * Build 'admin' processor group
     *
     * @return CP\SOCProcessorBuilder
     */
    protected function buildSocProcessor()
    {
        return new CP\SOCProcessorBuilder($this->builder);
    }

}