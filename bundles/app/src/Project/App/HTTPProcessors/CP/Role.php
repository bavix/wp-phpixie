<?php

namespace Project\App\HTTPProcessors\CP;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\CPProtected;
use Project\App\Model;

class Role extends CPProtected
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

        return $this->render('app:cp/role/role');
    }

}