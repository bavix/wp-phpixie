<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;

class Brand extends SOCProtected
{

    /**
     * @param $name
     *
     * @return null|\PHPixie\ORM\Models\Type\Database\Implementation\Entity
     * @throws \PHPixie\ORM\Exception\Query
     */
    protected function getBrandByName($name)
    {
        return $this->components->orm()->query(Model::BRAND)
            ->where('name', $name)
            ->findOne();
    }

    /**
     * @param $id
     *
     * @return null|\PHPixie\ORM\Models\Type\Database\Implementation\Entity
     * @throws \PHPixie\ORM\Exception\Query
     */
    protected function getBrandById($id)
    {
        return $this->components->orm()->query(Model::BRAND)
            ->in($id)
            ->findOne();
    }

    public function addAction(Request $request)
    {
        if ($request->method() === 'POST')
        {
            $name = $request->data()->getRequired('name');

            $name = mb_strtoupper($name);

            if (!empty($name))
            {
                $orm = $this->components->orm();

                $brand = $this->getBrandByName($name);

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
            return $request->data()->get();
        }

        /**
         * @var \Project\Framework\Builder $builder
         */
        $builder = $this->builder->frameworkBuilder();

//        $page = $request->query()->get('page');

        $helper = $builder->helper();

        $logCount = $helper->logCountByModel(Model::BRAND, $id);
//        $logPager = $helper->logPager(Model::BRAND, $id, $page);

        $brand = $this->getBrandById($id);

        $this->assign('brand', $brand);

        $this->assign('logCount', $logCount);

//        $this->assign('pager', $logPager);

        return $this->render('cp:soc/brand/edit');
    }

    public function deleteAction(Request $request)
    {
        if ($this->user->hasPermission('cp.soc.brand.delete'))
        {
            return $request->method();
            if ($request->method() === 'DELETE')
            {
//                $id = $request->query()->getRequired('id');
//
//                $brand = $this->getBrandById($id);
//
//                $brand->delete();
//
//                return [$brand->isDeleted()]; // todo
            }

            return [];
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