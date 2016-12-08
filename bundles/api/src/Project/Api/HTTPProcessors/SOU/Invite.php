<?php

namespace Project\Api\HTTPProcessors\SOU;

use Project\Api\HTTPProcessors\Processor\SOUProtected;

class Invite extends SOUProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}