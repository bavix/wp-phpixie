<?php

namespace Project\App\HTTPProcessors\CP;

use Project\App\HTTPProcessors\Processor\CPProtected;

class Wheel extends CPProtected
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