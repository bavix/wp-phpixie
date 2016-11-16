<?php

namespace Project\Cp\HTTPProcessors\SOW;

use Project\Cp\HTTPProcessors\Processor\SOWProtected;

class BoltPattern extends SOWProtected
{

    public function defaultAction()
    {
        return $this->render('cp:sow/bolt-pattern/default');
    }

}