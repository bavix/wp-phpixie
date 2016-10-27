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

}