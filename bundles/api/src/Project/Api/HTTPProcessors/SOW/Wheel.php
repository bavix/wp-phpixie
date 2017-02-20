<?php

namespace Project\Api\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\HTTPProcessors\Processor\SOWProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;

class Wheel extends SOWProtected
{

    // default
    public function defaultPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.wheel.add'))
        {
            throw new \Exception('Access denied');
        }

        $brandId = $request->data()->getRequired('brandId');

        if (empty($brandId) || !is_numeric($brandId))
        {
            throw new \InvalidArgumentException('Brand ID not found');
        }

        $collectionId = $request->data()->get('collectionId');

//        if (empty($collectionId) || !is_numeric($collectionId))
//        {
//            throw new \InvalidArgumentException('Collection ID not found');
//        }

        $name = $request->data()->getRequired('name');

        if (empty($name))
        {
            throw new \InvalidArgumentException('Name not found');
        }

        $orm = $this->components->orm();

        $name = mb_strtoupper($name);

        /**
         * @var \PHPixie\ORM\Wrappers\Type\Database\Query $wheel
         */
        $wheel = $orm->query(Model::WHEEL);
        $wheel->where('brandId', $brandId);
        $wheel->where('collectionId', $collectionId);
        $wheel->where('name', $name);

        $wheel = $wheel->findOne();

        if (!$wheel)
        {
            $wheel = $orm->createEntity(Model::WHEEL);

            $wheel->brandId      = $brandId;
            $wheel->collectionId = $collectionId;
            $wheel->name         = $name;

            $wheel->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $wheel->asObject(true);
    }

    /**
     * @api               {get} /auth/sow/wheel Wheel List
     * @apiName           Wheel List
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  [page=1] set num page [default 1]
     * @apiParam        {Number}  [limit=50] set limit [default 50]
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
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

        $wheel = $this->components->orm()->query(Model::WHEEL);

        $this->query($wheel, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $wheel, $limit, $preload);

        return [
            'currentPage' => $pager->currentPage(),
            'pageSize'    => $pager->pageSize(),
            'itemCount'   => $pager->itemCount(),
            'pageCount'   => $pager->pageCount(),
            'data'        => $pager->getCurrentItems()->asArray(true)
        ];
    }


    public function itemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.wheel.delete'))
        {
            throw new \Exception('Access denied');
        }

        $id = $request->attributes()->getRequired('id');

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($id)
            ->findOne();

        if (!$wheel)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        $wheel->delete();

        return $wheel->asObject(true);
//        else if ($this->loggedUser()->hasPermission('cp.soc.brand.pull-request'))
//        {
//            // pull request
//
//            return [];
//        }

    }

    /**
     * @api               {get} /auth/sow/wheel/<id> Wheel Item
     * @apiName           Wheel Item
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id wheelId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @param Request $request
     *
     * @return array
     */
    public function itemGetAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $preload = $request->query()->get('preload', []);

        try
        {
            $wheel = $this->components->orm()->query(Model::WHEEL)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$wheel)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $wheel->asObject(true);
    }

}