<?php

namespace Project\App\HTTPProcessors;

use Project\App\Builder;

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
     * @return CP\Brand
     */
    protected function buildBrandProcessor()
    {
        return new CP\Brand($this->builder);
    }

    /**
     * @return CP\Dealer
     */
    protected function buildDealerProcessor()
    {
        return new CP\Dealer($this->builder);
    }

    /**
     * @return CP\Wheel
     */
    protected function buildWheelProcessor()
    {
        return new CP\Wheel($this->builder);
    }

    /**
     * @return CP\Heading
     */
    protected function buildHeadingProcessor()
    {
        return new CP\Heading($this->builder);
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

}