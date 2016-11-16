<?php

namespace Project\Cp\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOUProtected;
use Project\Model;
use Project\App\Role;

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
        $orm      = $this->components->orm();
        $paginate = $this->components->paginateOrm();

        $query = $request->query();

        $page = (int)$query->get('page', 1);

        if (!$page)
        {
            $page = 1;
        }

        $limit  = 24;
        $page   = $page > 0 ? $page - 1 : 0;
        $offset = $limit * $page;

        $userQuery = $orm->query(Model::USER);

        $userQuery->offset($offset);
        $userQuery->limit($limit);

        /**
         * @var $pager \PHPixie\Paginate\Pager
         */
        $pager = $paginate->queryPager($userQuery, $limit);

        $pager->setCurrentPage($page + 1);

        $this->variables['pager'] = $pager;

        return $this->render('cp:sou/user/default');
    }

    public function editAction(Request $request)
    {
        if (!$this->user->hasPermission('cp.user.edit'))
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
        if (!$this->user->hasPermission('cp.user.delete'))
        {
            $this->accessDenied();
        }

        return [];
    }

    public function pullRequestAction(Request $request)
    {
        if (!$this->user->hasPermission('cp.user.pull-request'))
        {
            $this->accessDenied();
        }

        return [];
    }

    public function profileAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $user = $this->components->orm()
            ->query(Model::USER)
            ->where('id', (int)$id)
            ->findOne();

        $this->variables['user'] = $user;

        return $this->render('cp:sou/user/profile');
    }

}