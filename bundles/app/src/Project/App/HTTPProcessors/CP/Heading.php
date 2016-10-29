<?php

namespace Project\App\HTTPProcessors\CP;

use Project\App\HTTPProcessors\Processor\CPProtected;

class Heading extends CPProtected
{

    public function defaultAction()
    {
        return [__METHOD__];
    }

}