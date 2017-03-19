<?php

namespace Project\Cp\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOWProtected;
use Project\Model;

class Collection extends SOWProtected
{

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.sow.collection@add');

        $query = $request->query();
        $page  = $query->get('page');

        /**
         * @var $filter array
         */
        $filter = $query->get('filter', []);

        $orm = $this->components->orm();

        $collectionQuery = $orm->query(Model::COLLECTION)
            ->where('parentId', 0)
            ->orderDescendingBy('createdAt');

        if (is_array($filter))
        {
            foreach ($filter as $name => $value)
            {
                if (is_array($value))
                {
                    $collectionQuery->orWhere($name, 'in', $value);
                }
                else
                {
                    $collectionQuery->orWhere($name, $value);
                }
            }
        }

        $cAllCount = $collectionQuery->count();

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $collectionQuery);

        $this->assign('pager', $pager);
        $this->assign('count', $cAllCount);
        $this->assign('filter', $filter);

        return $this->render('cp:sow/collection/default');
    }

    public function addAction(Request $request)
    {
        return $this->render('cp:sow/collection/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $collection = $this->components->orm()->query(Model::COLLECTION)
            ->in($id)
            ->findOne();

        $this->assign('id', $id);
        $this->assign('item', $collection);

        return $this->render('cp:sow/collection/edit');
    }

}