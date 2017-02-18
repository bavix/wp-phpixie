<?php

namespace Project\Api\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\HTTPProcessors\Processor\SOWProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;

class BoltPattern extends SOWProtected
{

    // default
    public function defaultPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.bolt-pattern.add'))
        {
            throw new \Exception('Access denied');
        }

        $bolt = $request->data()->getRequired('bolt');
        $bolt = trim($bolt);

        if (!is_numeric($bolt))
        {
            throw new \Exception('Bolt is not number');
        }

        $pcd = $request->data()->getRequired('pcd');
        $pcd = trim($pcd);

        if (!is_numeric($pcd))
        {
            throw new \Exception('PCD is not number');
        }

        $orm = $this->components->orm();

        /**
         * @var \PHPixie\ORM\Wrappers\Type\Database\Query $boltPattern
         */
        $boltPattern = $orm->query(Model::BOLT_PATTERN);
        $boltPattern->where('bolt', $bolt);
        $boltPattern->where('pcd', $pcd);

        $boltPattern = $boltPattern->findOne();

        if (!$boltPattern)
        {
            $boltPattern = $orm->createEntity(Model::BOLT_PATTERN);

            $boltPattern->bolt = $bolt;
            $boltPattern->pcd  = $pcd;
            $boltPattern->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $boltPattern->asObject(true);
    }

    /**
     * @api               {get} /auth/sow/bolt-pattern Bolt Pattern List
     * @apiName           Bolt Pattern List
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam          page set num page [default 1]
     * @apiParam          limit set limit [default 50]
     * @apiParam          preload loading relationships
     *
     * @apiParam          sort order by id desc
     * @apiParam          terms filter equal id = 4
     * @apiParam          queries filter LIKE %4%
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

        $boltPattern = $this->components->orm()->query(Model::BOLT_PATTERN);

        $this->query($boltPattern, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $boltPattern, $limit, $preload);

        return $pager->getCurrentItems()->asArray(true);
    }


    public function itemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.bolt-pattern.delete'))
        {
            throw new \Exception('Access denied');
        }

        $id = $request->attributes()->getRequired('id');

        $boltPattern = $this->components->orm()->query(Model::BOLT_PATTERN)
            ->in($id)
            ->findOne();

        if (!$boltPattern)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        $boltPattern->delete();

        return $boltPattern->asObject(true);
//        else if ($this->loggedUser()->hasPermission('cp.soc.brand.pull-request'))
//        {
//            // pull request
//
//            return [];
//        }

    }

    /**
     * @api               {get} /auth/sow/bolt-pattern/<id> Bolt Pattern Item
     * @apiName           Bolt Pattern Item
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam          id boltPatternId
     *
     * @apiParam          page set num page [default 1]
     * @apiParam          limit set limit [default 50]
     * @apiParam          preload loading relationships
     *
     * @apiParam          sort order by id desc
     * @apiParam          terms filter equal id = 4
     * @apiParam          queries filter LIKE %4%
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
            $boltPattern = $this->components->orm()->query(Model::BOLT_PATTERN)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$boltPattern)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $boltPattern->asObject(true);
    }


}