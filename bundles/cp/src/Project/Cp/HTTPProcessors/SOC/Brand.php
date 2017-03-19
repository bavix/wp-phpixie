<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;

class Brand extends SOCProtected
{

    public function addAction(Request $request)
    {
        return $this->render('cp:soc/brand/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $this->addItemButton('cp.soc.brand@add');

        /**
         * @var \Project\Framework\Builder $builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page = $request->query()->get('page');

        $helper = $builder->helper();

        $logCount = $helper->logCountByModel(Model::BRAND, $id);
        $logPager = $helper->logPager(Model::BRAND, $id, $page, 100);

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($id)
            ->findOne();

        $socials = $this->components->orm()->query(Model::SOCIAL)
            ->find();

        $brands = $this->components->orm()->query(Model::BRAND)->find();

        $this->assign('id', $id);
        $this->assign('item', $brand);
        $this->assign('items', $brands);

        $this->assign('socials', $socials);

        $this->assign('logCount', $logCount);

        $this->assign('pager', $logPager);

        return $this->render('cp:soc/brand/edit');
    }

    /**
     * Brand List
     */

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \PHPixie\Paginate\Exception
     */
    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.soc.brand@add');

        $query = $request->query();
        $page  = $query->get('page');

        /**
         * @var $filter array
         */
        $filter = $query->get('filter', []);

        $orm = $this->components->orm();

        $brandQuery = $orm->query(Model::BRAND)
            ->orderDescendingBy('createdAt');

        if (is_array($filter))
        {
            foreach ($filter as $name => $value)
            {
                if (is_array($value))
                {
                    $brandQuery->orWhere($name, 'in', $value);
                }
                else
                {
                    $brandQuery->orWhere($name, $value);
                }
            }
        }

        $brandAllCount = $brandQuery->count();

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $brandQuery);

//        $optionSelectBrands = $orm->query(Model::BRAND)
//            ->orderAscendingBy('name')
//            ->find();

        $this->assign('pager', $pager);
        $this->assign('count', $brandAllCount);
        $this->assign('filter', $filter);

//        $this->assign('optionSelectBrands', $optionSelectBrands);

        return $this->render('cp:soc/brand/default');
    }

}