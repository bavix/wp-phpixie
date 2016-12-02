<?php

namespace Project\Api\HTTPProcessors\SOU;

use Cake\Chronos\Chronos;
use Openbuildings\Swiftmailer\CssInlinerPlugin;
use PHPixie\HTTP\Request;
use Project\Api\HTTPProcessors\Processor\SOUProtected;
use Project\Model;
use Project\Role;

class Invite extends SOUProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

}