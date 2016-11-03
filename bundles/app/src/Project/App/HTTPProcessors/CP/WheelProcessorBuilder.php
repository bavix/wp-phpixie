<?php

namespace Project\App\HTTPProcessors\CP;

use Project\App\Builder;

/**
 * Builds processors in the 'app.admin' namespace
 */
class WheelProcessorBuilder extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
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
    protected $attributeName = 'wheelProcessor';

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
     * @return Wheel\Wheel
     */
    protected function buildWheelProcessor()
    {
        return new Wheel\Wheel($this->builder);
    }

}