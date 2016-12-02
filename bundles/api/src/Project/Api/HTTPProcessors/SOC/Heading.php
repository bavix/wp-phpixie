<?php

namespace Project\Api\HTTPProcessors\SOC;

use Project\Api\HTTPProcessors\Processor\SOCProtected;

class Heading extends SOCProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}