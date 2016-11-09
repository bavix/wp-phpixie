<?php

namespace Project\App\HTTPProcessors\CP\SOW;

use Project\App\HTTPProcessors\Processor\SOWProtected;

class BoltPattern extends SOWProtected
{

    public function defaultAction()
    {
        return $this->render('app:cp/sow/bolt-pattern/default');
    }

}