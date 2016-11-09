<?php

namespace Project\App\HTTPProcessors\CP;

use Project\App\Builder;
use Project\Util;

/**
 * Builds processors in the 'app.admin' namespace
 */
class SOUProcessorBuilder extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Specifies which request attribute will be used
     * to select a processor.
     *
     * @var string
     */
    protected $attributeName = 'nextProcessor';

    /**
     * Constructor
     *
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    protected function getProcessorNameFor($httpRequest)
    {
        $processorName = $httpRequest->attributes()->get($this->attributeName);
        $processorName = Util::camelCase($processorName);

        return $processorName;
    }

    /**
     * @return SOU\User
     */
    protected function buildUserProcessor()
    {
        return new SOU\User($this->builder);
    }

    /**
     * @return SOU\Permission
     */
    protected function buildPermissionProcessor()
    {
        return new SOU\Permission($this->builder);
    }

    /**
     * @return SOU\Role
     */
    protected function buildRoleProcessor()
    {
        return new SOU\Role($this->builder);
    }

    /**
     * @return SOU\Invite
     */
    protected function buildInviteProcessor()
    {
        return new SOU\Invite($this->builder);
    }

}