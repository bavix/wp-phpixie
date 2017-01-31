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

        if ($request->method() === 'POST')
        {
            var_dump($request->data()->get());
            die;

            // todo add checking data [post]

            $brand = $this->components->orm()->query(Model::BRAND)
                ->in($id)
                ->findOne();

            if (!$brand)
            {
                throw new \Exception();
            }

            $name = $request->data()->get('name', $brand->name);

            $isCarbon = $request->data()->get('isCarbon');
            $isCarbon = (int)filter_var($isCarbon, FILTER_VALIDATE_BOOLEAN, array(
                'options' => array(
                    'default' => false
                )
            ));

            $isOffRoad = $request->data()->get('isOffRoad');
            $isOffRoad = (int)filter_var($isOffRoad, FILTER_VALIDATE_BOOLEAN, array(
                'options' => array(
                    'default' => false
                )
            ));

            $active = $request->data()->get('active');
            $active = (int)filter_var($active, FILTER_VALIDATE_BOOLEAN, array(
                'options' => array(
                    'default' => false
                )
            ));

            $brand->name      = $name;
            $brand->isCarbon  = $isCarbon;
            $brand->isOffRoad = $isOffRoad;
            $brand->active    = $active;

            $brand->save();
        }

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

        $this->assign('brand', $brand);

        $this->assign('socials', $socials);
        $this->assign('brands', $brands);

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