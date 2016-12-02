<?php

namespace Project\App\HTTPProcessors;

/**
 * Base processor
 */
abstract class Processor extends \Project\Extension\Processor
{

    /**
     * @var string
     */
    protected $resolverPath = 'app.processor';

}