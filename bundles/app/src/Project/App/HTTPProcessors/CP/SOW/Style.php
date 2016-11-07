<?php

namespace Project\App\HTTPProcessors\CP\SOW;

use Project\App\HTTPProcessors\Processor\WheelProtected;

class Style extends WheelProtected
{

    public function defaultAction()
    {
        return [__METHOD__];
    }

}