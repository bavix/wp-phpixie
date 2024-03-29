<?php

namespace Project\Api\HTTPProcessors\SOW;

use Embed\Embed;
use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\Exceptions\Unauthorized;
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
     * @api               {get} /sow/wheel Wheel List
     * @apiName           Wheel List
     * @apiGroup          SOW
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
    public function defaultGetAction(Request $request)
    {
        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

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

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->where('active', 1);

        $this->query($wheel, $request, [
            'sort' => [
                'popular'   => 'desc',
                'createdAt' => 'desc',
            ]
        ]);

        $pager = $builder
            ->helper()
            ->pager($page, $wheel, $limit, $preload);

        return $this->pager($pager);
    }

    public function videoItemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.wheel.delete'))
        {
            throw new \Exception('Access denied');
        }

        $attributes = $request->attributes();
        $wheelId    = $attributes->get('id');
        $videoId    = $attributes->get('nextId');

        if ($this->components->orm()->query('wheelsVideo')
            ->where('wheelId', $wheelId)
            ->where('videoId', $videoId)
            ->delete()
        )
        {
            RESTFUL::setStatus(REST::NO_CONTENT);
            throw new \InvalidArgumentException('Success');
        }

        throw new \InvalidArgumentException('Video not found');
    }

    public function imageItemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.wheel.delete'))
        {
            throw new \Exception('Access denied');
        }

        $attributes = $request->attributes();
        $wheelId    = $attributes->get('id');
        $imageId    = $attributes->get('nextId');

        if ($this->components->orm()->query('wheelsImage')
            ->where('wheelId', $wheelId)
            ->where('imageId', $imageId)
            ->delete()
        )
        {
            RESTFUL::setStatus(REST::NO_CONTENT);
            throw new \InvalidArgumentException('Success');
        }

        throw new \InvalidArgumentException('Video not found');
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

    public function itemPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.wheel.edit'))
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

        $data = $request->data();

        $name         = $data->getRequired('name');
        $brandName    = $data->getRequired('brandName');
        $collectionId = $data->get('collectionId') ?: null;
        $styleId      = $data->get('styleId', $wheel->styleId);
        $isCustom     = (int)($data->get('isCustom', 'off') === 'on');
        $isRetired    = (int)($data->get('isRetired', 'off') === 'on');
        $active       = (int)($data->get('active', 'off') === 'on');
        
        if ($collectionId)
        {

            $collection = $this->components->orm()->query(Model::COLLECTION)
                ->in($collectionId)
                ->findOne();

            $brandId = $collection->brandId;
        }
        else
        {
            $brandId = $this->components->orm()->query(Model::BRAND)
                ->where('name', $brandName)
                ->findOne()
                ->id();
        }

        if (empty($styleId))
        {
            $styleId = null;
        }

        $wheel->name         = $name;
        $wheel->brandId      = $brandId;
        $wheel->collectionId = $collectionId;
        $wheel->styleId      = $styleId;
        $wheel->isCustom     = $isCustom;
        $wheel->isRetired    = $isRetired;
        $wheel->active       = $active;

        $wheel->save();

        return $wheel->asObject(true);

    }

    public function videoPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.wheel.edit'))
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

        $url = $request->data()->getRequired('url');
        $url = $url[0] === 'h' ? $url : 'http://' . $url;

        $info = Embed::create($url);

        if ($info->providerName !== 'YouTube')
        {
            RESTFUL::setError('provider');
            throw new \InvalidArgumentException('It is the reference not of YouTube');
        }

        $video = $this->components->orm()->query(Model::VIDEO)
            ->where('url', $info->url)
            ->findOne();

        if (!$video)
        {
            $video = $this->components->orm()->createEntity(Model::VIDEO);

            $video->identifier = $info->getResponse()->getUrl()->getQueryParameter('v');

            $video->url      = $info->url;
            $video->provider = $info->providerName;

            $video->title       = $info->title;
            $video->description = $info->description;

            $video->image       = $info->image;
            $video->imageWidth  = $info->imageWidth;
            $video->imageHeight = $info->imageHeight;

            $video->width       = $info->width;
            $video->height      = $info->height;
            $video->aspectRatio = $info->aspectRatio;

            $video->authorName = $info->authorName;
            $video->authorUrl  = $info->authorUrl;

            $video->userId = $this->loggedUser()->id();

            $video->save();
        }

        $wheel->videos->add($video);

        return $video->asObject(true);
    }

    /**
     * @api               {get} /sow/wheel/<id> Wheel Item
     * @apiName           Wheel Item
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
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

    /**
     * @api               {get} /sow/wheel/<id>/comment Wheel Comment List
     * @apiName           Wheel Comment List
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [less] filter less id < 4
     * @apiParam        {String[]}  [greater] filter greater id > 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     * @apiSuccessExample Success-Response:
     *                    HTTP/1.1 200 OK
     *                   {
     *                       "currentPage": 1,
     *                       "pageSize": 50,
     *                       "itemCount": "1",
     *                       "pageCount": 1,
     *                       "data": [
     *                           {
     *                               "id": "1",
     *                               "text": "hello world!",
     *                               "userId": "1",
     *                               "createdAt": "2017-02-24 16:09:08",
     *                               "updatedAt": "2017-02-24 16:09:08"
     *                           }
     *                       ]
     *                   }
     *
     * @param Request $request
     *
     * @return array
     */
    public function commentGetAction(Request $request)
    {
        $wheelId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        try
        {
            $wheelComment = $this->components->orm()
                ->query(Model::COMMENT)
                ->relatedTo(
                    'wheels',
                    $this->components->orm()
                        ->query(Model::WHEEL)
                        ->in($wheelId)
                );

            $this->query($wheelComment, $request);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        $pager = $builder
            ->helper()
            ->pager($page, $wheelComment, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /sow/wheel/<id>/similar Wheel Similar List
     * @apiName           Wheel Similar List
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
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
     * @return array
     */
    public function similarGetAction(Request $request)
    {
        $wheelId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($wheelId)
            ->findOne();

        if (!$wheel)
        {
            RESTFUL::setError('wheel');
            throw new \InvalidArgumentException('Wheel not found');
        }

        $wheelQuery = $this->components->orm()->query(Model::WHEEL)
            ->where('id', '!=', $wheelId)
            ->where('styleId', '!=', null)
            ->where('styleId', $wheel->styleId);

        $this->query($wheelQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $wheelQuery, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {post} /sow/wheel/<id>/comment Wheel Comment Add
     * @apiName           Wheel Comment Add
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id        wheelId
     * @apiParam        {string}  text      text
     *
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
     * @return array
     */
    public function commentPostAction(Request $request)
    {
        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $id = $request->attributes()->getRequired('id');

        if (!is_numeric($id))
        {
            throw new \InvalidArgumentException('ID is not numeric!');
        }

        $text = $request->data()->getRequired('text');
        $text = strip_tags($text);

        if (empty($text))
        {
            throw new \InvalidArgumentException('Text is empty!');
        }

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($id)
            ->findOne();

        if (!$wheel)
        {
            throw new \InvalidArgumentException('Wheel not found');
        }

        $comment         = $this->components->orm()->createEntity(Model::COMMENT);
        $comment->userId = $user->id();
        $comment->text   = $text;

        $comment->save();

        $wheelComment            = $this->components->orm()->createEntity('wheelsComment');
        $wheelComment->wheelid   = $wheel->id();
        $wheelComment->commentId = $comment->id();

        $wheelComment->save();

        if ($wheelComment->id())
        {
            RESTFUL::setStatus(REST::CREATED);

            return $comment->asObject(true);
        }

        RESTFUL::setError('comment');
        throw new \InvalidArgumentException('Can\'t make comment on wheel');
    }

    /**
     * @api               {post} /sow/wheel/<id>/favourite Wheel Favourite Add
     * @apiName           Wheel Favourite Add
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
     * @param Request $request
     *
     * @return array
     */
    public function favouritePostAction(Request $request)
    {
        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $id = $request->attributes()->getRequired('id');

        if (!is_numeric($id))
        {
            throw new \InvalidArgumentException('ID is not numeric!');
        }

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($id)
            ->findOne();

        if (!$wheel)
        {
            throw new \InvalidArgumentException('Wheel not found');
        }

        $wheelFavourite          = $this->components->orm()->createEntity('wheelsFavourite');
        $wheelFavourite->wheelid = $wheel->id();
        $wheelFavourite->userId  = $user->id();

        $wheelFavourite->save();

        if ($wheelFavourite->id())
        {
            RESTFUL::setStatus(REST::CREATED);

            return [
                'created' => true,
                'count'   => $wheel->favourites->query()->count()
            ];
        }

        RESTFUL::setError('favourite');
        throw new \InvalidArgumentException('Can\'t make favourite on wheel');
    }

    /**
     * @api               {delete} /sow/wheel/<id>/favourite Wheel favourite Remove
     * @apiName           Wheel favourite Remove
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
     * @param Request $request
     *
     * @return array
     */
    public function favouriteDeleteAction(Request $request)
    {
        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $id = $request->attributes()->getRequired('id');

        if (!is_numeric($id))
        {
            throw new \InvalidArgumentException('ID is not numeric!');
        }

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($id)
            ->findOne();

        if (!$wheel)
        {
            throw new \InvalidArgumentException('Wheel not found');
        }

        $result = $this->components->orm()->query('wheelsFavourite')
            ->where('wheelId', $wheel->id())
            ->where('userId', $user->id())
            ->delete();

        if ($result)
        {
            RESTFUL::setStatus(REST::NO_CONTENT);

            return null; // restful api
        }

        RESTFUL::setError('favourite');
        throw new \InvalidArgumentException('Can\'t remove favourite on wheel');
    }

    /**
     * @api               {post} /sow/wheel/<id>/like Wheel Like Add
     * @apiName           Wheel Like Add
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
     * @param Request $request
     *
     * @return array
     */
    public function likePostAction(Request $request)
    {
        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $id = $request->attributes()->getRequired('id');

        if (!is_numeric($id))
        {
            throw new \InvalidArgumentException('ID is not numeric!');
        }

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($id)
            ->findOne();

        if (!$wheel)
        {
            throw new \InvalidArgumentException('Wheel not found');
        }

        $wheelLike          = $this->components->orm()->createEntity('wheelsLike');
        $wheelLike->wheelid = $wheel->id();
        $wheelLike->userId  = $user->id();

        $wheelLike->save();

        if ($wheelLike->id())
        {
            RESTFUL::setStatus(REST::CREATED);

            return [
                'created' => true,
                'count'   => $wheel->likes->query()->count()
            ];
        }

        RESTFUL::setError('like');
        throw new \InvalidArgumentException('Can\'t make like on wheel');
    }

    /**
     * @api               {delete} /sow/wheel/<id>/like Wheel Like Remove
     * @apiName           Wheel Like Remove
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
     * @param Request $request
     *
     * @return array
     */
    public function likeDeleteAction(Request $request)
    {
        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $id = $request->attributes()->getRequired('id');

        if (!is_numeric($id))
        {
            throw new \InvalidArgumentException('ID is not numeric!');
        }

        $wheel = $this->components->orm()->query(Model::WHEEL)
            ->in($id)
            ->findOne();

        if (!$wheel)
        {
            throw new \InvalidArgumentException('Wheel not found');
        }

        $result = $this->components->orm()->query('wheelsLike')
            ->where('wheelId', $wheel->id())
            ->where('userId', $user->id())
            ->delete();

        if ($result)
        {
            RESTFUL::setStatus(REST::NO_CONTENT);

            return null; // restful api
        }

        RESTFUL::setError('like');
        throw new \InvalidArgumentException('Can\'t remove like on wheel');
    }

    /**
     * @api               {get} /sow/wheel/<id>/video Wheel Video List
     * @apiName           Wheel Video List
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
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
     * @return array
     */
    public function videoGetAction(Request $request)
    {
        $wheelId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $wheel = $this->components->orm()
            ->query(Model::WHEEL)
            ->in($wheelId)
            ->findOne();

        if (!$wheel)
        {
            RESTFUL::setError('wheel');
            throw new \InvalidArgumentException('Wheel not found');
        }

        $videoQuery = $wheel->videos->query();

        $this->query($videoQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $videoQuery, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /sow/wheel/<id>/image Wheel Video List
     * @apiName           Wheel Video List
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        wheelId
     *
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
     * @return array
     */
    public function imageGetAction(Request $request)
    {
        $wheelId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $wheel = $this->components->orm()
            ->query(Model::WHEEL)
            ->in($wheelId)
            ->findOne();

        if (!$wheel)
        {
            RESTFUL::setError('wheel');
            throw new \InvalidArgumentException('Wheel not found');
        }

        $imageQuery = $wheel->images->query();

        $this->query($imageQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $imageQuery, $limit, $preload);

        return $this->pager($pager);
    }

//    public function debugGetAction(Request $request)
//    {
//
//        $video = $this->components->orm()->query(Model::VIDEO)->findOne();
//
//        if (!$video)
//        {
//
//            $urls = [
//                'https://www.youtube.com/watch?v=JLl_Wli9C18',
//                'https://www.youtube.com/watch?v=Yi6LU3Z7obM',
//                'https://www.youtube.com/watch?v=cFNHuIIMTto',
//                'https://www.youtube.com/watch?v=m9V3zTneXjM',
//                'https://www.youtube.com/watch?v=Nnbs6K49Edo',
//            ];
//
//            $wheel = $this->components->orm()->query(Model::WHEEL)
//                ->in($request->attributes()->getRequired('id'))
//                ->findOne();
//
//            foreach ($urls as $url)
//            {
//                $info = \Embed\Embed::create($url);
//                $list = [
//                    'url'      => $info->url,
//                    'provider' => $info->providerName,
//
//                    'title'       => $info->title,
//                    'description' => $info->description,
//
//                    'image'       => $info->image,
//                    'imageWidth'  => $info->imageWidth,
//                    'imageHeight' => $info->imageHeight,
//
//                    'width'       => $info->width,
//                    'height'      => $info->height,
//                    'aspectRatio' => $info->aspectRatio,
//
//                    'authorName' => $info->authorName,
//                    'authorUrl'  => $info->authorUrl,
//
//                    'userId' => 1,
//                ];
//
//                $video = $this->components->orm()->createEntity(Model::VIDEO);
//
//                foreach ($list as $column => $value)
//                {
//                    $video->{$column} = $value;
//                }
//
//                $video->save();
//
//                $wheel->videos->add($video);
//            }
//
//        }
//
//        return $video->asObject(true);
//    }


}