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

        $logCount = $helper->logCountByModel(Model::BRAND, $id);
        $logPager = $helper->logPager(Model::BRAND, $id, $page, 150);

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($id)
            ->findOne();

        $this->assign('brand', $brand);

        $this->assign('logCount', $logCount);

        $this->assign('pager', $logPager);

        return $this->render('cp:soc/brand/edit');
    }

    public function deleteAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        if ($this->user->hasPermission('cp.soc.brand.delete'))
        {
            if ($request->method() === 'DELETE')
            {
                $brand = $this->components->orm()->query(Model::BRAND)
                    ->in($id)
                    ->findOne();

                $brand->delete();

                return [
                    'status' => 'success',
                    'data'   => [
                        'isDeleted'   => $brand->isDeleted(),
                        'pullRequest' => false
                    ]
                ];
            }

            return [];
        }
        else if ($this->user->hasPermission('cp.soc.brand.pull-request'))
        {
            // pull request

            return [
                'status' => 'error',
                'data'   => [
                    'isDeleted'   => false,
                    'pullRequest' => false
                ]
            ];
        }

        throw new \Exception();
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

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $pager = $builder->helper()->pager($page, $brandQuery);

        $this->assign('pager', $pager);

        return $this->render('cp:soc/brand/default');
    }

}