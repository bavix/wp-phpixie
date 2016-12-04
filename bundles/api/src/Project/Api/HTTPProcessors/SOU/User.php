<?php

namespace Project\Api\HTTPProcessors\SOU;

use Project\Api\HTTPProcessors\Processor\SOUProtected;

class User extends SOUProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}