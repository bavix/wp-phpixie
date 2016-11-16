<?php

namespace Project\Cp\HTTPProcessors\SOW;

use Project\Cp\HTTPProcessors\Processor\SOWProtected;

class Wheel extends SOWProtected
{

    public function defaultAction()
    {
        return $this->render('cp:sow/wheel/default');
    }

}