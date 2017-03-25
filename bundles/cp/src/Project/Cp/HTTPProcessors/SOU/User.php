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

        $itemQuery = $orm->query(Model::USER);

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        /**
         * @var $pager \PHPixie\Paginate\Pager
         */
        $pager = $builder->helper()->pager(
            $page,
            $itemQuery,
            50,
            ['image', 'role']
        );

        $this->assign('count', $itemQuery->count());
        $this->assign('pager', $pager);

        return $this->render('cp:sou/user/default');
    }

    public function editAction(Request $request)
    {
        if (!$this->user->hasPermission('cp.sou.user.edit'))
        {
            return $this->accessDenied($request);
        }

        $id = $request->attributes()->getRequired('id');

        $item = $this->components->orm()->query(Model::USER)
            ->in($id)
            ->findOne(['image']);

        $this->assign('id', $id);
        $this->assign('item', $item);

        return $this->render('cp:sou/user/edit');
    }

}