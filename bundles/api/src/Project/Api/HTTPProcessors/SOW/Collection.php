<?php

namespace Project\Api\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\HTTPProcessors\Processor\SOWProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;

class Collection extends SOWProtected
{

    // default
    public function defaultPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.collection.add'))
        {
            throw new \Exception('Access denied');
        }

        $orm = $this->components->orm();

        $parentId = $request->data()->get('parentId', 0);

        if (!$parentId)
        {
            $brandId = $request->data()->getRequired('brandId');
        }
        else
        {
            $collection = $orm->query(Model::COLLECTION)
                ->in($parentId)
                ->findOne();

            if (!$collection)
            {
                throw new \InvalidArgumentException('parentId not found');
            }

            $brandId = $collection->brandId;
        }

        $name = $request->data()->getRequired('name');

        if (empty($name))
        {
            throw new \InvalidArgumentException('Name is empty');
        }

        $name = mb_strtoupper($name);

        /**
         * @var \PHPixie\ORM\Wrappers\Type\Database\Query $collection
         */
        $collection = $orm->query(Model::COLLECTION);
        $collection->where('parentId', $parentId);
        $collection->where('brandId', $brandId);
        $collection->where('name', $name);

        $collection = $collection->findOne();

        if (!$collection)
        {
            $collection = $orm->createEntity(Model::COLLECTION);

            $collection->parentId = $parentId;
            $collection->brandId  = $brandId;
            $collection->name     = $name;
            $collection->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $collection->asObject(true);
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \PHPixie\Paginate\Exception
     */
    public function defaultGetAction(Request $request)
    {
        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $collection = $this->components->orm()->query(Model::COLLECTION);

        $this->query($collection, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $collection, $limit, $preload);

        return $pager->getCurrentItems()->asArray(true);
    }


    public function itemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.collection.delete'))
        {
            throw new \Exception('Access denied');
        }

        $id = $request->attributes()->getRequired('id');

        $collection = $this->components->orm()->query(Model::COLLECTION)
            ->in($id)
            ->findOne();

        if (!$collection)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }
        
        $collection->delete();

        return $collection->asObject(true);
//        else if ($this->loggedUser()->hasPermission('cp.soc.brand.pull-request'))
//        {
//            // pull request
//
//            return [];
//        }

    }

    public function itemGetAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $preload = $request->query()->get('preload', []);

        try
        {
            $collection = $this->components->orm()->query(Model::COLLECTION)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$collection)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $collection->asObject(true);
    }


}