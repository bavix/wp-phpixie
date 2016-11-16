<?php

namespace Project\Cp\HTTPProcessors\Processor;

/**
 * Base processor that allows only logged in users
 */
abstract class SOCProtected extends CPProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'cp.soc.processor';

}