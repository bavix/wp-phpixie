<?php

namespace Project\Api\HTTPProcessors;

use Project\Cp\Builder;
use Project\Extension\Util;

/**
 * Builds processors in the 'cp.admin' namespace
 */
class SOCProcessorBuilder extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
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
     * @return SOC\Brand
     */
    protected function buildBrandProcessor()
    {
        return new SOC\Brand($this->builder);
    }

    /**
     * @return SOC\Dealer
     */
    protected function buildDealerProcessor()
    {
        return new SOC\Dealer($this->builder);
    }

    /**
     * @return SOC\Heading
     */
    protected function buildHeadingProcessor()
    {
        return new SOC\Heading($this->builder);
    }

}