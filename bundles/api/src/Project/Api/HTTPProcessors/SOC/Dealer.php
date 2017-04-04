<?php

namespace Project\Api\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\Exceptions\Unauthorized;
use Project\Api\HTTPProcessors\Processor\SOCProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;
use Project\ORM\Dealer\Query;

class Dealer extends SOCProtected
{

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \Exception
     */
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

    public function addressPostAction(Request $request)
    {
        $dealerId = $request->attributes()->getRequired('id');

        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($dealerId)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setError('dealer');
            throw new \InvalidArgumentException('Dealer not found');
        }

        $address         = $this->components->orm()->createEntity(Model::ADDRESS);
        $address->userId = $user->id();

        /**
         * @var $list array
         */
        $list = $request->data()->get();

        if (!$list)
        {
            RESTFUL::setError('POST is empty');
            throw new \InvalidArgumentException();
        }

        foreach ($list as $key => $value)
        {
            $data = null;

            if (!empty($value))
            {
                $data = $value;
            }

            $address->{$key} = $data;
        }

        $address->save();

        $dealersAddress            = $this->components->orm()->createEntity('dealersAddress');
        $dealersAddress->dealerId   = $dealerId;
        $dealersAddress->addressId = $address->id();

        $dealersAddress->save();

        return $address->asObject(true);
    }

    public function addressItemDeleteAction(Request $request)
    {
        $dealerId   = $request->attributes()->getRequired('id');
        $addressId = $request->attributes()->getRequired('nextId');

        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $dealersAddress = $this->components->orm()->query('dealersAddress')
            ->where('dealerId', $dealerId)
            ->where('addressId', $addressId)
            ->findOne();

        if (!$dealersAddress)
        {
            RESTFUL::setError('dealersAddress');
            throw new \InvalidArgumentException('DealersAddress not found');
        }

        if ($dealersAddress->delete())
        {
            RESTFUL::setStatus(REST::NO_CONTENT);

            return [
                'success' => true
            ];
        }

        RESTFUL::setStatus('address');
        throw new \InvalidArgumentException('Error remove address from dealer');
    }

    /**
     * @api               {get} /soc/dealer/<id> Dealer Item
     * @apiName           Dealer Item
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id dealerId
     *
     * @apiParam        {Number}  [page=1] set num page [default 1]
     * @apiParam        {Number}  [limit=50] set limit [default 50]
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @param Request $request
     *
     * @return mixed
     */
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

    public function itemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.dealer.delete'))
        {
            throw new \Exception('Access denied');
        }

        $id = $request->attributes()->getRequired('id');

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($id)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        $dealer->delete();

        return $dealer->asObject(true);
//        else if ($this->loggedUser()->hasPermission('cp.soc.dealer.pull-request'))
//        {
//            // pull request
//
//            return [];
//        }

    }

    public function itemPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.dealer.edit'))
        {
            throw new \Exception('Access denied');
        }

        $id = $request->attributes()->getRequired('id');

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($id)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        $data = $request->data();

        $name = $data->getRequired('name');
        $web  = $data->get('web');

        if (empty($web))
        {
            $web = null;
        }

        $active     = $data->get('active') === 'on';

        $dealer->name       = $name;
        $dealer->web        = $web;
        $dealer->active     = (int)$active;

        $dealer->save();

        return $dealer->asObject(true);
    }

    /**
     * @api               {get} /soc/dealer Dealer List
     * @apiName           Dealer List
     * @apiGroup          SOC
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

        $dealer = $this->components->orm()->query(Model::DEALER);

        $this->query($dealer, $request);

        $pager = $builder->helper()->pager($page, $dealer, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function socialPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.dealersocial.add'))
        {
            throw new \Exception('Access denied');
        }

        $dealerId  = $request->attributes()->getRequired('id');
        $socialId = $request->data()->getRequired('type');
        $url      = trim($request->data()->getRequired('url'));

        $urlValidate = filter_var($url, FILTER_VALIDATE_URL);

        if (!$urlValidate)
        {
            throw new \InvalidArgumentException('not a valid URL');
        }

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($dealerId)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('Dealer not found');
        }

        $dealerSocial = $this->components->orm()->query(Model::DEALER_SOCIAL)
            ->where('dealerId', $dealerId)
            ->where('socialId', $socialId)
            ->where('url', $url)
            ->findOne();

        if (!$dealerSocial)
        {
            $dealerSocial           = $this->components->orm()->createEntity(Model::DEALER_SOCIAL);
            $dealerSocial->dealerId  = $dealerId;
            $dealerSocial->socialId = $socialId;
            $dealerSocial->url      = $url;
            $dealerSocial->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $dealerSocial->asObject(true);
    }

    /**
     * @api               {get} /soc/dealer/<id>/social Dealer Social List
     * @apiName           Dealer Social List
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id dealerId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [less] filter less id < 4
     * @apiParam        {String[]}  [greater] filter greater id > 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     *
     * @param Request $request
     *
     * @return array
     */
    public function socialGetAction(Request $request)
    {
        $dealerId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $socialQuery = $this->components->orm()->query(Model::DEALER_SOCIAL)
            ->where('dealerId', $dealerId);

        $this->query($socialQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $socialQuery, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /soc/dealer/<id>/social/<nextId> Dealer Social Item
     * @apiName           Dealer Social Item
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id        dealerId
     * @apiParam        {Number}  nextId    socialId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function socialItemGetAction(Request $request)
    {
        $dealerSocialId = $request->attributes()->getRequired('nextId');

        $preload = $request->query()->get('preload', []);

        try
        {
            $dealerSocial = $this->components->orm()->query(Model::DEALER_SOCIAL)
                ->in($dealerSocialId)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$dealerSocial)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('DealerSocial not found');
        }

        return $dealerSocial->asObject(true);
    }

    public function socialItemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.dealersocial.delete'))
        {
            throw new \Exception('Access denied');
        }

        $dealerId  = $request->attributes()->getRequired('id');
        $socialId = $request->attributes()->getRequired('nextId');

        $dealerSocial = $this->components->orm()->query(Model::DEALER_SOCIAL)
            ->where('dealerId', $dealerId)
            ->where('socialId', $socialId)
            ->findOne();

        if (!$dealerSocial)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('DealerSocial not found');
        }

        $dealerSocial->delete();

        return $dealerSocial->asObject(true);
    }

    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function headingPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.dealerheading.add'))
        {
            throw new \Exception('Access denied');
        }

        $dealerId   = $request->attributes()->getRequired('id');
        $headingId = $request->data()->getRequired('id');

        $headingIdValidate = filter_var($headingId, FILTER_VALIDATE_INT);

        if (!$headingIdValidate)
        {
            throw new \InvalidArgumentException('not a valid Heading Id');
        }

        $heading = $this->components->orm()->query(Model::HEADING)
            ->in($headingId)
            ->findOne();

        if (!$heading)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('Heading not found');
        }

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($dealerId)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('Dealer not found');
        }

        $dealerHeading = $this->components->orm()->query(Model::DEALER_HEADING)
            ->where('dealerId', $dealerId)
            ->where('headingId', $headingId)
            ->findOne();

        if (!$dealerHeading)
        {
            $dealerHeading            = $this->components->orm()->createEntity(Model::DEALER_HEADING);
            $dealerHeading->dealerId   = $dealerId;
            $dealerHeading->headingId = $headingId;
            $dealerHeading->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $dealerHeading->asObject(true);
    }

    /**
     * @api               {get} /soc/dealer/<id>/heading Dealer Heading List
     * @apiName           Dealer Heading List
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id        dealerId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [less] filter less id < 4
     * @apiParam        {String[]}  [greater] filter greater id > 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     *
     * @param Request $request
     *
     * @return array
     */
    public function headingGetAction(Request $request)
    {
        $dealerId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($dealerId)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setError('dealer');
            throw new \InvalidArgumentException('Dealer not found');
        }

        $headingQuery = $dealer->headings->query();

        $this->query($headingQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $headingQuery, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /soc/dealer/<id>/heading/<nextId> Dealer Heading Item
     * @apiName           Dealer Heading Item
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id            dealerId
     * @apiParam        {Number}  nextId        headingId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function headingItemGetAction(Request $request)
    {
        $dealerHeadingId = $request->attributes()->getRequired('nextId');

        $preload = $request->query()->get('preload', []);

        try
        {
            $dealerHeading = $this->components->orm()->query(Model::DEALER_HEADING)
                ->in($dealerHeadingId)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$dealerHeading)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('DealerHeading not found');
        }

        return $dealerHeading->asObject(true);
    }

    public function headingItemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.dealerheading.delete'))
        {
            throw new \Exception('Access denied');
        }

        $dealerId = $request->attributes()->getRequired('id');
        $heading = $request->attributes()->getRequired('nextId');

        $dealerHeading = $this->components->orm()->query(Model::DEALER_HEADING)
            ->where('dealerId', $dealerId)
            ->where('headingId', $heading)
            ->findOne();

        if (!$dealerHeading)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('DealerHeading not found');
        }

        $dealerHeading->delete();

        return $dealerHeading->asObject(true);
    }

    /**
     * @api               {get} /soc/dealer/<id>/dealer Dealer Dealer List
     * @apiName           Dealer Dealer List
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id        dealerId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [less] filter less id < 4
     * @apiParam        {String[]}  [greater] filter greater id > 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     *
     * @param Request $request
     *
     * @return array
     */
    public function dealerGetAction(Request $request)
    {
        $dealerId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($dealerId)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setError('dealer');
            throw new \InvalidArgumentException('Dealer not found');
        }

        $dealerQuery = $dealer->dealers->query();

        $this->query($dealerQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $dealerQuery, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /soc/dealer/<id>/address Dealer Address List
     * @apiName           Dealer Address List
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        dealerId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [less] filter less id < 4
     * @apiParam        {String[]}  [greater] filter greater id > 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     *
     * @param Request $request
     *
     * @return array
     */
    public function addressGetAction(Request $request)
    {
        $dealerId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $dealer = $this->components->orm()
            ->query(Model::DEALER)
            ->in($dealerId)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setError('dealer');
            throw new \InvalidArgumentException('Dealer not found');
        }

        $query = $dealer->addresses->query();

        $this->query($query, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $query, $limit, $preload);

        return $this->pager($pager);
    }

}