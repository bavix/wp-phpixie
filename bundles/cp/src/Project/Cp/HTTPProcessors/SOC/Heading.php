<?php

namespace Project\Cp\HTTPProcessors\SOC;

use Project\Cp\HTTPProcessors\Processor\SOCProtected;

class Heading extends SOCProtected
{

    public function defaultAction()
    {
        return $this->render('cp:soc/heading/default');
    }

}