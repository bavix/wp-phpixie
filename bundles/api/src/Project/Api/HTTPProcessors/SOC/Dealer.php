<?php

namespace Project\Api\HTTPProcessors\SOC;

use Project\Api\HTTPProcessors\Processor\SOCProtected;

class Dealer extends SOCProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}