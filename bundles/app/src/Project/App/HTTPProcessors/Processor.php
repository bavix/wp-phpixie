<?php

namespace Project\App\HTTPProcessors;

use PHPixie\BundleFramework\Components;
use PHPixie\DefaultBundle\Processor\HTTP\Actions;
use PHPixie\HTTP\Responses\Response;
use Project\App\Builder;
use Project\Extension\Util;
use Project\ORM\User\User;

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