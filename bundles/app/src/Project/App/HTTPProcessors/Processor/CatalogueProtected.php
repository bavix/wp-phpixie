<?php

namespace Project\App\HTTPProcessors\Processor;

use PHPixie\Framework\Processors\HTTP\Response\NotFound;
use PHPixie\HTTP\Request;
use PHPixie\Processors\Exception;
use Project\App\Builder;
use Project\App\HTTPProcessors\Processor;
use Project\App\Model;
use Project\App\ORM\User\User;
use Project\Breadcrumb;

/**
 * Base processor that allows only logged in users
 */
abstract class CatalogueProtected extends CPProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'app.cp.catalogue.processor';

}