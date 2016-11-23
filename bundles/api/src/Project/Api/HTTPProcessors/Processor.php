<?php

namespace Project\Api\HTTPProcessors;

/**
 * Base processor
 */
abstract class Processor extends \Project\Extension\Processor
{

    /**
     * @var string
     */
    protected $resolverPath = 'api.processor';

}