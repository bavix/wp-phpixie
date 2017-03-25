<?php

namespace Project\Api\HTTPProcessors\SOU;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\Exceptions\Unauthorized;
use Project\Api\HTTPProcessors\Processor\SOUProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;

class User extends SOUProtected
{

    /**
     * @api               {get} /sou/user User List
     * @apiName           User List
     * @apiGroup          SOU
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  [page=1] set num page [default 1]
     * @apiParam        {Number}  [limit=50] set limit [default 50]
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @param Request $request
     *
     * @return array
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

        $user = $this->components->orm()->query(Model::USER);

        $this->query($user, $request);

        $pager = $builder->helper()->pager($page, $user, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /sou/user/<id> User Item
     * @apiName           User Item
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
            $user = $this->components->orm()->query(Model::USER)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$user)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $user->asObject(true);
    }


    public function itemPostAction(Request $request)
    {
        $user = $this->loggedUser();
//        $user = $this->components->orm()->query(Model::USER)->findOne();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        if (!$user->hasPermission('cp.sou.user.edit'))
        {
            throw new \InvalidArgumentException('Access Denied');
        }

        $lastname = $request->data()->get('lastname');

        if (!empty($lastname) && $lastname !== $user->lastname)
        {
            $user->lastname = $lastname;
        }

        $name = $request->data()->get('name');

        if (!empty($name) && $name !== $user->name)
        {
            $user->name = $name;
        }

        $email = $request->data()->get('email');

        if (!empty($email) && $email !== $user->email)
        {
            $check = $this->components->orm()->query(Model::USER)
                ->where('email', $email)
                ->findOne();

            if ($check)
            {
                RESTFUL::setError('email');
                throw new \InvalidArgumentException('Email exists');
            }

            $user->email = $email;
        }

        $about = $request->data()->get('about');

        if (!empty($about) && $about !== $user->about)
        {
            $user->about = $about;
        }

        $user->save();

        return $user->asObject(true);
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
     * @apiParam        {String[]}  [less] filter less id < 4
     * @apiParam        {String[]}  [greater] filter greater id > 4
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
            throw new Unauthorized();
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
     * @apiParam        {String[]}  [less] filter less id < 4
     * @apiParam        {String[]}  [greater] filter greater id > 4
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
            throw new Unauthorized();
        }

        $wheel = $user->likeWheels->query();

        $this->query($wheel, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $wheel, $limit, $preload);

        return $this->pager($pager);
    }

}