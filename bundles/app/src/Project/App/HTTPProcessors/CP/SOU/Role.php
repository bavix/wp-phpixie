<?php

namespace Project\App\HTTPProcessors\CP\SOU;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\SOUProtected;
use Project\App\Model;

class Role extends SOUProtected
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function defaultAction(Request $request)
    {
        $orm = $this->components->orm();

        $roles = $orm->query(Model::ROLE)
            ->find();

        $this->variables['roles'] = $roles;

        return $this->render('app:cp/sou/role/default');
    }

}