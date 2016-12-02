<?php

namespace Project\Api\HTTPProcessors\SOW;

use Project\Api\HTTPProcessors\Processor\SOWProtected;

class Wheel extends SOWProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}