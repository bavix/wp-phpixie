<?php

namespace Project\Api\HTTPProcessors\Processor;

/**
 * Base processor that allows only logged in users
 */
abstract class SOUProtected extends APIProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'api.sou.processor';

}