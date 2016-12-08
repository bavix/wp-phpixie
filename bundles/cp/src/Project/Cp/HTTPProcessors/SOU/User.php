<?php

namespace Project\Cp\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\App\Role;
use Project\Cp\HTTPProcessors\Processor\SOUProtected;
use Project\Model;

class User extends SOUProtected
{

    /**
     * @param Request $request
     *
     * @return string
     * @throws \PHPixie\ORM\Exception\Query
     * @throws \PHPixie\Paginate\Exception
     */
    public function defaultAction(Request $request)
    {
        $query = $request->query();

        $orm  = $this->components->orm();
        $page = $query->get('page');

        $userQuery = $orm->query(Model::USER);

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        /**
         * @var $pager \PHPixie\Paginate\Pager
         */
        $pager = $builder->helper()->pager($page, $userQuery, 24);

        $this->assign('pager', $pager);

        return $this->render('cp:sou/user/default');
    }

    public function editAction(Request $request)
    {
        if (!$this->user->hasPermission('cp.sou.user.edit'))
        {
            $this->accessDenied();
        }

        // if not load
        //  => throw new exception

        if ($request->method() === 'POST')
        {
            // update profile user
        }

        return $this->render('cp:sou/user/edit');
    }

    public function deleteAction(Request $request)
    {
        if (!$this->user->hasPermission('cp.sou.user.delete'))
        {
            $this->accessDenied();
        }

        return [];
    }

    public function pullRequestAction(Request $request)
    {
        if (!$this->user->hasPermission('cp.sou.user.pull-request'))
        {
            $this->accessDenied();
        }

        return [];
    }

    public function profileAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $user = $this->components->orm()->query(Model::USER)
            ->in($id)
            ->findOne();

        $this->assign('profile', $user);

        return $this->render('cp:sou/user/profile');
    }

}