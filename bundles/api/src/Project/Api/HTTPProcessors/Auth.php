<?php

namespace Project\Api\HTTPProcessors;

class Auth extends Processor
{

    protected $isProtected = false;

    public function defaultAction()
    {
        return [];
    }

}