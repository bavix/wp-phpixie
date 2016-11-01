<?php

namespace Project\App\HTTPProcessors\CP;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\CPProtected;
use Project\App\Model;

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

        $userQuery = $orm->query(Model::User);

        $query = $request->query();

        $page = $query->get('page', 0);

        if (!is_int($page))
        {
            $page = 0;
        }

        $limit  = 40;
        $offset = $page * $limit;

        $userQuery->limit($limit);
        $userQuery->offset($offset);

        $pager = $paginate->queryPager($userQuery, $limit);

        $this->variables['pager'] = $pager;

        return $this->render('app:cp/user/user');
    }

}