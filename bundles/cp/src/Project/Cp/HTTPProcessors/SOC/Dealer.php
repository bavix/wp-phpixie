<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;

class Dealer extends SOCProtected
{

    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.soc.dealer@add');

        $query = $request->query();
        $page  = $query->get('page');

        /**
         * @var $filter array
         */
        $filter = $query->get('filter', []);

        $orm = $this->components->orm();

        $dealerQuery = $orm->query(Model::DEALER)
            ->orderDescendingBy('createdAt');

        if (is_array($filter))
        {
            foreach ($filter as $name => $value)
            {
                if (is_array($value))
                {
                    $dealerQuery->orWhere($name, 'in', $value);
                }
                else
                {
                    $dealerQuery->orWhere($name, $value);
                }
            }
        }

        $dealerAllCount = $dealerQuery->count();

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $dealerQuery, 50, ['image']);

//        $optionSelectBrands = $orm->query(Model::BRAND)
//            ->orderAscendingBy('name')
//            ->find();

        $this->assign('pager', $pager);
        $this->assign('count', $dealerAllCount);
        $this->assign('filter', $filter);

//        $this->assign('optionSelectBrands', $optionSelectBrands);

        return $this->render('cp:soc/dealer/default');
    }

    public function addAction(Request $request)
    {
        return $this->render('cp:soc/dealer/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $this->addItemButton('cp.soc.dealer@add');

        /**
         * @var \Project\Framework\Builder $builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page = $request->query()->get('page');

        $helper = $builder->helper();

        $logCount = $helper->logCountByModel(Model::DEALER, $id);
        $logPager = $helper->logPager(Model::DEALER, $id, $page, 100);

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($id)
            ->findOne();

        $socials = $this->components->orm()->query(Model::SOCIAL)
            ->find();

        $dealers = $this->components->orm()->query(Model::DEALER)->find();

        $this->assign('id', $id);
        $this->assign('item', $dealer);
        $this->assign('items', $dealers);

        $this->assign('socials', $socials);

        $this->assign('logCount', $logCount);

        $this->assign('pager', $logPager);

        return $this->render('cp:soc/dealer/edit');
    }

}