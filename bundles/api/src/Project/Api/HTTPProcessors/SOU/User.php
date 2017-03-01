<?php

namespace Project\Api\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\Api\HTTPProcessors\Processor\SOUProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;

class User extends SOUProtected
{

    public function defaultGetAction()
    {
        return [__METHOD__];
    }

    /**
     * @api               {get} /sou/user/<id>/favourite-wheel Wheel Favourite List
     * @apiName           Wheel Favourite List
     * @apiGroup          SOU
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id userId
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
    public function favouriteWheelGetAction(Request $request)
    {
        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $id = $request->attributes()->getRequired('id');

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $user = $this->loggedUser();

        if ($user)
        {
            $preload = array_merge($preload, [

                'likes' => [
                    'queryCallback' => function ($query) use ($user)
                    {
                        /**
                         * @var $query \PHPixie\Database\Driver\PDO\Query\Type\Select
                         */
                        $query
                            ->fields('id')
                            ->where('users.id', $user->id())
                            ->limit(1);
                    }
                ],

                'favourites' => [
                    'queryCallback' => function ($query) use ($user)
                    {
                        /**
                         * @var $query \PHPixie\Database\Driver\PDO\Query\Type\Select
                         */
                        $query
                            ->fields('id')
                            ->where('users.id', $user->id())
                            ->limit(1);
                    }
                ]

            ]);
        }

        $user = $this->components->orm()->query(Model::USER)
            ->in($id)
            ->findOne();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        $wheel = $user->favouriteWheels->query();

        $this->query($wheel, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $wheel, $limit, $preload);

        return $this->pager($pager);
    }


    /**
     * @api               {get} /sou/user/<id>/like-wheel Wheel Like List
     * @apiName           Wheel Like List
     * @apiGroup          SOU
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id userId
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
    public function likeWheelGetAction(Request $request)
    {
        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $id = $request->attributes()->getRequired('id');

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $user = $this->loggedUser();

        if ($user)
        {
            $preload = array_merge($preload, [

                'likes' => [
                    'queryCallback' => function ($query) use ($user)
                    {
                        /**
                         * @var $query \PHPixie\Database\Driver\PDO\Query\Type\Select
                         */
                        $query
                            ->fields('id')
                            ->where('users.id', $user->id())
                            ->limit(1);
                    }
                ],

                'favourites' => [
                    'queryCallback' => function ($query) use ($user)
                    {
                        /**
                         * @var $query \PHPixie\Database\Driver\PDO\Query\Type\Select
                         */
                        $query
                            ->fields('id')
                            ->where('users.id', $user->id())
                            ->limit(1);
                    }
                ]

            ]);
        }

        $user = $this->components->orm()->query(Model::USER)
            ->in($id)
            ->findOne();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        $wheel = $user->likeWheels->query();

        $this->query($wheel, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $wheel, $limit, $preload);

        return $this->pager($pager);
    }

}