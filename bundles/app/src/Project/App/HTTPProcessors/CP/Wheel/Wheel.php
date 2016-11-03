<?php

namespace Project\App\HTTPProcessors\CP\Wheel;

use Project\App\HTTPProcessors\Processor\WheelProtected;

class Wheel extends WheelProtected
{

    public function defaultAction()
    {
        return [__METHOD__];
    }


    public function boltPatternAction()
    {
        return [__METHOD__];
    }


    public function stylesAction()
    {
        return [__METHOD__];
    }

}