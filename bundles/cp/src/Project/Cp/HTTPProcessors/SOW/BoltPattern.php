<?php

namespace Project\Cp\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOWProtected;
use Project\Model;

class BoltPattern extends SOWProtected
{

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.sow.bolt-pattern@add');

        $query = $request->query();
        $page  = $query->get('page');

        /**
         * @var $filter array
         */
        $filter = $query->get('filter', []);

        $orm = $this->components->orm();

        $boltPatternQuery = $orm->query(Model::BOLT_PATTERN)
            ->orderDescendingBy('createdAt');

        if (is_array($filter))
        {
            foreach ($filter as $name => $value)
            {
                if (is_array($value))
                {
                    $boltPatternQuery->orWhere($name, 'in', $value);
                }
                else
                {
                    $boltPatternQuery->orWhere($name, $value);
                }
            }
        }

        $bpAllCount = $boltPatternQuery->count();

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $boltPatternQuery);

        $this->assign('pager', $pager);
        $this->assign('count', $bpAllCount);
        $this->assign('filter', $filter);

        return $this->render('cp:sow/bolt-pattern/default');
    }

    public function addAction(Request $request)
    {
        return $this->render('cp:sow/bolt-pattern/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $boltPattern = $this->components->orm()->query(Model::BOLT_PATTERN)
            ->in($id)
            ->findOne();

        $this->assign('boltPattern', $boltPattern);

        return $this->render('cp:sow/bolt-pattern/edit');
    }

}