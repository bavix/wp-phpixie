<?php

namespace Project\App\HTTPProcessors\CP;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\CPProtected;
use Project\App\Model;
use Project\App\Role;

class User extends CPProtected
{

    /**
     * @param Request $request
     *
     * @return string
     * @throws \PHPixie\ORM\Exception\Query
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

        $limit  = 15;
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

        return $this->render('app:cp/user/user');
    }

}