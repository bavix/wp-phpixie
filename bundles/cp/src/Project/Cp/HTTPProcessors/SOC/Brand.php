<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;

class Brand extends SOCProtected
{

    public function addAction(Request $request)
    {
        if ($request->method() === 'POST')
        {
            $name = $request->data()->getRequired('name');

            $name = mb_strtoupper($name);

            if (!empty($name))
            {
                $orm = $this->components->orm();

                $brand = $orm->query(Model::BRAND)
                    ->where('name', $name)
                    ->findOne();

                if (!$brand)
                {
                    $brand = $orm->createEntity(Model::BRAND);

                    $brand->name = $name;
                    $brand->save();
                }

                $resolverPath = 'cp.soc.brand@edit.' . $brand->id();

                return $this->redirectWithUtil($resolverPath);
            }
        }

        return $this->render('cp:soc/brand/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        return $this->render('cp:soc/brand/edit');
    }

    public function deleteAction(Request $request)
    {
        // ajax
        return [];
    }

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

        $limit  = 50;
        $page   = $page > 0 ? $page - 1 : 0;
        $offset = $limit * $page;

        $brandQuery = $orm->query(Model::BRAND)
            ->orderDescendingBy('id');

        $brandQuery->offset($offset);
        $brandQuery->limit($limit);

        /**
         * @var $pager \PHPixie\Paginate\Pager
         */
        $pager = $paginate->queryPager($brandQuery, $limit);

        $pager->setCurrentPage($page + 1);

        $this->variables['pager'] = $pager;

        return $this->render('cp:soc/brand/default');
    }

}