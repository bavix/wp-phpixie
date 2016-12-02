<?php

namespace Project\Api\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\Api\HTTPProcessors\Processor\SOUProtected;
use Project\Model;

class User extends SOUProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}