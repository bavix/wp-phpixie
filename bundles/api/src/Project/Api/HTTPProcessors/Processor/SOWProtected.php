<?php

namespace Project\Api\HTTPProcessors\Processor;

/**
 * Base processor that allows only logged in users
 */
abstract class SOWProtected extends APIProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'api.sow.processor';

}