<?php

namespace Project\App\HTTPProcessors\CP;

use Project\App\Builder;
use Project\Util;

/**
 * Builds processors in the 'app.admin' namespace
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

}