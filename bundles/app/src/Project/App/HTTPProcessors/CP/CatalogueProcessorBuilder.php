<?php

namespace Project\App\HTTPProcessors\CP;

use Project\App\Builder;
use Project\Util;

/**
 * Builds processors in the 'app.admin' namespace
 */
class CatalogueProcessorBuilder extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
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
     * @return Catalogue\Brand
     */
    protected function buildBrandProcessor()
    {
        return new Catalogue\Brand($this->builder);
    }

    /**
     * @return Catalogue\Dealer
     */
    protected function buildDealerProcessor()
    {
        return new Catalogue\Dealer($this->builder);
    }

    /**
     * @return Catalogue\Heading
     */
    protected function buildHeadingProcessor()
    {
        return new Catalogue\Heading($this->builder);
    }

}