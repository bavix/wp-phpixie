<?php

namespace Project\Cp\HTTPProcessors;

use Project\Cp\Builder;
use Project\Extension\Util;

/**
 * Builds processors in the 'cp.admin' namespace
 */
class SettingsProcessorBuilder extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
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
    protected $attributeName = 'nextProcessor';

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
     * @return Settings\API
     */
    protected function buildApiProcessor()
    {
        return new Settings\API($this->builder);
    }

    /**
     * @return Settings\Cache
     */
    protected function buildCacheProcessor()
    {
        return new Settings\Cache($this->builder);
    }

}