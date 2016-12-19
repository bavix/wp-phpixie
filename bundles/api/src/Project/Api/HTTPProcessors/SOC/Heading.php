<?php

namespace Project\Api\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\HTTPProcessors\Processor\SOCProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;
use Project\ORM\Heading\Query;

class Heading extends SOCProtected
{

    // default
    public function defaultPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.heading.add'))
        {
            throw new \Exception('Access denied');
        }

        $title = $request->data()->getRequired('title');
        $title = mb_strtoupper($title);
        $title = trim($title);

        if (empty($title))
        {
            throw new \Exception('Name is empty');
        }

        $orm = $this->components->orm();

        /**
         * @var Query $heading
         */
        $heading = $orm->query(Model::HEADING);
        $heading = $heading->findByTitle($title);

        if (!$heading)
        {
            $heading = $orm->createEntity(Model::HEADING);

            $heading->title = $title;
            $heading->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $heading->asObject(true);
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

        $brand = $this->components->orm()->query(Model::HEADING);

        $this->query($brand, $request);

        $pager = $builder->helper()->pager($page, $brand, $limit, $preload);

        return $pager->getCurrentItems()->asArray(true);
    }

    public function itemGetAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $preload = $request->query()->get('preload', []);

        try
        {
            $heading = $this->components->orm()->query(Model::HEADING)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$heading)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $heading->asObject(true);
    }

}