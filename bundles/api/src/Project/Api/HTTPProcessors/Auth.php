<?php

namespace Project\Api\HTTPProcessors;

class Auth extends Processor
{

    protected function pdo()
    {
        return $this->components->database()->get()->pdo();
    }

    public function authorizeAction()
    {
//        \OAuth2\GrantType\
    }

    public function tokenAction()
    {

    }

    public function resourceAction()
    {

    }

    public function defaultAction()
    {
        return [];
    }

}