<?php

namespace Project\App\HTTPProcessors\CP\SOU;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\SOUProtected;
use Project\App\Model;

class Permission extends SOUProtected
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function defaultAction(Request $request)
    {
        return $this->render('app:cp/sou/permission/default');
    }

}