<?php

namespace Project\Api\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\HTTPProcessors\Processor\SOCProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;
use Project\ORM\Dealer\Query;

class Dealer extends SOCProtected
{

    // default
    public function defaultPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.dealer.add'))
        {
            throw new \Exception('Access denied');
        }

        $name = $request->data()->getRequired('name');
        $name = mb_strtoupper($name);
        $name = trim($name);

        if (empty($name))
        {
            throw new \Exception('Name is empty');
        }

        $orm = $this->components->orm();

        /**
         * @var Query $dealer
         */
        $dealer = $orm->query(Model::DEALER);
        $dealer = $dealer->findByName($name);

        if (!$dealer)
        {
            $dealer = $orm->createEntity(Model::DEALER);

            $dealer->name = $name;
            $dealer->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $dealer->asObject(true);
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

        $dealer = $this->components->orm()->query(Model::DEALER);

        $this->query($dealer, $request);

        $pager = $builder->helper()->pager($page, $dealer, $limit, $preload);

        return $pager->getCurrentItems()->asArray(true);
    }

    public function itemGetAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $preload = $request->query()->get('preload', []);

        try
        {
            $dealer = $this->components->orm()->query(Model::DEALER)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$dealer)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $dealer->asObject(true);
    }

}