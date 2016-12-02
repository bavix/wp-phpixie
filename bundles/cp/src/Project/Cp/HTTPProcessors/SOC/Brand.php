<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;
use Project\ORM\Brand\Query;

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

                /**
                 * @var Query $brand
                 */
                $brand = $orm->query(Model::BRAND);
                $brand = $brand->findByName($name);

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

        $this->addItemButton('cp.soc.brand@add');

        if ($request->method() === 'POST')
        {
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

        $modelsLog = [
            Model::BRAND,
            Model::BRAND_HEADING,
            Model::BRAND_SOCIAL,
            Model::BRAND_DEALER,
        ];

        $logCount = $helper->logCountByModel($modelsLog, $id);
        $logPager = $helper->logPager($modelsLog, $id, $page, 100);

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($id)
            ->findOne();

        $socials = $this->components->orm()->query(Model::SOCIAL)
            ->find();

        $this->assign('brand', $brand);
        $this->assign('socials', $socials);

        $this->assign('logCount', $logCount);

        $this->assign('pager', $logPager);

        return $this->render('cp:soc/brand/edit');
    }

    public function deleteAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

//        return (new Curl())->delete('/api/', []);
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

        $orm = $this->components->orm();

        $brandQuery = $orm->query(Model::BRAND)
            ->orderDescendingBy('createdAt');

        $brandAllCount = $brandQuery->count();

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $brandQuery);

        $this->assign('pager', $pager);
        $this->assign('count', $brandAllCount);

        return $this->render('cp:soc/brand/default');
    }

}