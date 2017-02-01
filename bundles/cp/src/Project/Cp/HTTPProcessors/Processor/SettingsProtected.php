<?php

namespace Project\Cp\HTTPProcessors\Processor;

/**
 * Base processor that allows only logged in users
 */
abstract class SettingsProtected extends CPProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'cp.settings.processor';

}