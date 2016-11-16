<?php

namespace Project\Cp\HTTPProcessors\Processor;

/**
 * Base processor that allows only logged in users
 */
abstract class SOUProtected extends CPProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'cp.sou.processor';

}