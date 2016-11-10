<?php

namespace Project\App\HTTPProcessors\CP;

use Project\App\HTTPProcessors\Processor\CPProtected;

class Settings extends CPProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/settings/default');
    }

}