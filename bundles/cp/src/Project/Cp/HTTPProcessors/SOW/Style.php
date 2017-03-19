<?php

namespace Project\Cp\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOWProtected;
use Project\Model;

class Style extends SOWProtected
{

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.sow.style@add');

        $query = $request->query();
        $page  = $query->get('page');

        /**
         * @var $filter array
         */
        $filter = $query->get('filter', []);

        $orm = $this->components->orm();

        $styleQuery = $orm->query(Model::STYLE)
            ->orderDescendingBy('createdAt');

        if (is_array($filter))
        {
            foreach ($filter as $name => $value)
            {
                if (is_array($value))
                {
                    $styleQuery->orWhere($name, 'in', $value);
                }
                else
                {
                    $styleQuery->orWhere($name, $value);
                }
            }
        }

        $styleAllCount = $styleQuery->count();

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $styleQuery);

        $this->assign('pager', $pager);
        $this->assign('count', $styleAllCount);
        $this->assign('filter', $filter);

        return $this->render('cp:sow/style/default');
    }

    /**
     * @return string
     */
    public function addAction()
    {
        return $this->render('cp:sow/style/add');
    }

    /**
     * @param Request $request
     *
     * @return string
     */
    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $style = $this->components->orm()->query(Model::STYLE)
            ->in($id)
            ->findOne();

        $this->assign('id', $id);
        $this->assign('item', $style);

        return $this->render('cp:sow/style/edit');
    }

}