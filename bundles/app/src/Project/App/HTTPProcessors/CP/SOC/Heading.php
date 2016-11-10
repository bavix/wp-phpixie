<?php

namespace Project\App\HTTPProcessors\CP\SOC;

use Project\App\HTTPProcessors\Processor\SOCProtected;

class Heading extends SOCProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/soc/heading/default');
    }

}