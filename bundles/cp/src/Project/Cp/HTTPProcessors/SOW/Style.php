<?php

namespace Project\Cp\HTTPProcessors\SOW;

use Project\Cp\HTTPProcessors\Processor\SOWProtected;

class Style extends SOWProtected
{

    public function defaultAction()
    {
        return $this->render('cp:sow/style/default');
    }

}