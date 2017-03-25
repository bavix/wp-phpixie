<?php

namespace Project\Cp\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOUProtected;
use Project\Model;

class Permission extends SOUProtected
{

    /**
     * @param Request $request
     *
     * @return mixed
     */
    public function defaultAction(Request $request)
    {

        $query = $request->query();

        $orm  = $this->components->orm();
        $page = $query->get('page');

        $itemQuery = $orm->query(Model::PERMISSION);

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        /**
         * @var $pager \PHPixie\Paginate\Pager
         */
        $pager = $builder->helper()->pager($page, $itemQuery);

        $this->assign('count', $itemQuery->count());
        $this->assign('pager', $pager);

        return $this->render('cp:sou/permission/default');
    }

}