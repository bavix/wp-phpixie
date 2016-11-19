<?php

namespace Project\Cp\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOUProtected;

class Permission extends SOUProtected
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function defaultAction(Request $request)
    {
        return $this->render('cp:sou/permission/default');
    }

}