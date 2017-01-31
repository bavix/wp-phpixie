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

        /**
         * @var $filter array
         */
        $filter = $query->get('filter', []);

        $orm = $this->components->orm();

        $styleQuery = $orm->query(Model::WHEEL)
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

        return $this->render('cp:sow/wheel/default');
    }

}