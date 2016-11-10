<?php

namespace Project\App\HTTPProcessors\CP\SOC;

use Project\App\HTTPProcessors\Processor\SOCProtected;

class Dealer extends SOCProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/soc/dealer/default');
    }

}