<?php

namespace Project\Cp\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOUProtected;
use Project\Model;

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

        return $this->render('cp:sou/role/default');
    }

}