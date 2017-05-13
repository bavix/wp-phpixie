<?php

namespace Project\Cp\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOWProtected;
use Project\Model;

class Wheel extends SOWProtected
{

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.sow.wheel@add');

        $query = $request->query();
        $page  = $query->get('page');

        $orm = $this->components->orm();

        $wheelQuery = $orm->query(Model::WHEEL);

        $this->query($wheelQuery, $request, [
            'sort' => [
                'createdAt' => 'asc'
            ]
        ]);

        $wheelAllCount = $wheelQuery->count();

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $wheelQuery, 50, [
            'collection',
            'style',
            'image'
        ]);

        $this->assign('pager', $pager);
        $this->assign('count', $wheelAllCount);

        return $this->render('cp:sow/wheel/default');
    }

    public function addAction(Request $request)
    {
        return $this->render('cp:sow/wheel/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($id)
            ->findOne(['brand', 'collection', 'style']);

        $styles = $this->components->orm()->query(Model::STYLE)->find();
        $collections = $this->components->orm()
            ->query(Model::COLLECTION)
            ->where('brandId', $wheel->brandId)
            ->find();

        $this->assign('id', $id);
        $this->assign('item', $wheel);
        $this->assign('styles', $styles);
        $this->assign('collections', $collections);
        $this->assign('imageType', 'wheel');

        return $this->render('cp:sow/wheel/edit');
    }

}