<?php

namespace Project\Cp\HTTPProcessors;

use Project\Cp\Builder;
use Project\Extension\Util;

/**
 * Builds processors in the 'cp.admin' namespace
 */
class SOWProcessorBuilder extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
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
     * @return SOW\Wheel
     */
    protected function buildWheelProcessor()
    {
        return new SOW\Wheel($this->builder);
    }

    /**
     * @return SOW\Style
     */
    protected function buildStyleProcessor()
    {
        return new SOW\Style($this->builder);
    }

    /**
     * @return SOW\BoltPattern
     */
    protected function buildBoltPatternProcessor()
    {
        return new SOW\BoltPattern($this->builder);
    }

    /**
     * @return SOW\Collection
     */
    protected function buildCollectionProcessor()
    {
        return new SOW\Collection($this->builder);
    }

}