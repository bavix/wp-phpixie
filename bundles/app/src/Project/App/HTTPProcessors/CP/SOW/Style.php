<?php

namespace Project\App\HTTPProcessors\CP\SOW;

use Project\App\HTTPProcessors\Processor\SOWProtected;

class Style extends SOWProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/sow/style/default');
    }

}