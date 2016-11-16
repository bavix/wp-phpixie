<?php

namespace Project\Cp\HTTPProcessors\Processor;

/**
 * Base processor that allows only logged in users
 */
abstract class SOWProtected extends CPProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'cp.sow.processor';

}