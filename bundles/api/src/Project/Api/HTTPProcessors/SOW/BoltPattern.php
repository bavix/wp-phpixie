<?php

namespace Project\Api\HTTPProcessors\SOW;

use Project\Api\HTTPProcessors\Processor\SOWProtected;

class BoltPattern extends SOWProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}