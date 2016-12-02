<?php

namespace Project\Api\HTTPProcessors\Processor;

use Project\Api\HTTPProcessors\AuthProcessor;
use Project\ORM\User\User;

/**
 * Base processor that allows only logged in users
 */
abstract class APIProtected extends AuthProcessor
{

    /**
     * @var User
     */
    protected $user;

}