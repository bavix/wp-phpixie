<?php

namespace Project\Cp\HTTPProcessors\SOC;

use Project\Cp\HTTPProcessors\Processor\SOCProtected;

class Brand extends SOCProtected
{

    public function defaultAction()
    {
        return $this->render('cp:soc/brand/default');
    }

}