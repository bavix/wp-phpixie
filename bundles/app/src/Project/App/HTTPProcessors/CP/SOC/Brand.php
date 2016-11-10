<?php

namespace Project\App\HTTPProcessors\CP\SOC;

use Project\App\HTTPProcessors\Processor\SOCProtected;

class Brand extends SOCProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/soc/brand/default');
    }

}