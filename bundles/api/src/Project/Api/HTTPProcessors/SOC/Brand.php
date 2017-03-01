<?php

namespace Project\Api\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\HTTPProcessors\Processor\SOCProtected;
use Project\Api\RESTFUL;
use Project\Framework\Builder;
use Project\Model;
use Project\ORM\Brand\Query;

class Brand extends SOCProtected
{

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \Exception
     */
    public function defaultPostAction(Request $request)
    {

        if (!$this->loggedUser()->hasPermission('cp.soc.brand.add'))
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
         * @var Query $brand
         */
        $brand = $orm->query(Model::BRAND);
        $brand = $brand->findByName($name);

        if (!$brand)
        {
            $brand = $orm->createEntity(Model::BRAND);

            $brand->name = $name;
            $brand->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $brand->asObject(true);

    }

    public function addressPostAction(Request $request)
    {
        $brandId = $request->attributes()->getRequired('id');

        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($brandId)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setError('brand');
            throw new \InvalidArgumentException('Brand not found');
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

        $brandsAddress            = $this->components->orm()->createEntity('brandsAddress');
        $brandsAddress->brandId   = $brandId;
        $brandsAddress->addressId = $address->id();

        $brandsAddress->save();

        return $address->asObject(true);
    }

    public function addressItemDeleteAction(Request $request)
    {
        $brandId   = $request->attributes()->getRequired('id');
        $addressId = $request->attributes()->getRequired('nextId');

        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        $brandsAddress = $this->components->orm()->query('brandsAddress')
            ->where('brandId', $brandId)
            ->where('addressId', $addressId)
            ->findOne();

        if (!$brandsAddress)
        {
            RESTFUL::setError('brandsAddress');
            throw new \InvalidArgumentException('BrandsAddress not found');
        }

        if ($brandsAddress->delete())
        {
            RESTFUL::setStatus(REST::NO_CONTENT);

            return [
                'success' => true
            ];
        }

        RESTFUL::setStatus('address');
        throw new \InvalidArgumentException('Error remove address from brand');
    }

    /**
     * @api               {get} /soc/brand/<id> Brand Item
     * @apiName           Brand Item
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id brandId
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
            $brand = $this->components->orm()->query(Model::BRAND)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$brand)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $brand->asObject(true);
    }

    public function itemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.brand.delete'))
        {
            throw new \Exception('Access denied');
        }

        $id = $request->attributes()->getRequired('id');

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($id)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        $brand->delete();

        return $brand->asObject(true);
//        else if ($this->loggedUser()->hasPermission('cp.soc.brand.pull-request'))
//        {
//            // pull request
//
//            return [];
//        }

    }

    /**
     * @api               {get} /soc/brand Brand List
     * @apiName           Brand List
     * @apiGroup          SOC
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

        $brand = $this->components->orm()->query(Model::BRAND);

        $this->query($brand, $request);

        $pager = $builder->helper()->pager($page, $brand, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function socialPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.brandsocial.add'))
        {
            throw new \Exception('Access denied');
        }

        $brandId  = $request->attributes()->getRequired('id');
        $socialId = $request->data()->getRequired('type');
        $url      = trim($request->data()->getRequired('url'));

        $urlValidate = filter_var($url, FILTER_VALIDATE_URL);

        if (!$urlValidate)
        {
            throw new \InvalidArgumentException('not a valid URL');
        }

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($brandId)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('Brand not found');
        }

        $brandSocial = $this->components->orm()->query(Model::BRAND_SOCIAL)
            ->where('brandId', $brandId)
            ->where('socialId', $socialId)
            ->where('url', $url)
            ->findOne();

        if (!$brandSocial)
        {
            $brandSocial           = $this->components->orm()->createEntity(Model::BRAND_SOCIAL);
            $brandSocial->brandId  = $brandId;
            $brandSocial->socialId = $socialId;
            $brandSocial->url      = $url;
            $brandSocial->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $brandSocial->asObject(true);
    }

    /**
     * @api               {get} /soc/brand/<id>/social Brand Social List
     * @apiName           Brand Social List
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id brandId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     * @param Request $request
     *
     * @return array
     */
    public function socialGetAction(Request $request)
    {
        $brandId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $socialQuery = $this->components->orm()->query(Model::BRAND_SOCIAL)
            ->where('brandId', $brandId);

        $this->query($socialQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $socialQuery, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /soc/brand/<id>/social/<nextId> Brand Social Item
     * @apiName           Brand Social Item
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id        brandId
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
        $brandSocialId = $request->attributes()->getRequired('nextId');

        $preload = $request->query()->get('preload', []);

        try
        {
            $brandSocial = $this->components->orm()->query(Model::BRAND_SOCIAL)
                ->in($brandSocialId)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$brandSocial)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('BrandSocial not found');
        }

        return $brandSocial->asObject(true);
    }

    public function socialItemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.brandsocial.delete'))
        {
            throw new \Exception('Access denied');
        }

        $brandId  = $request->attributes()->getRequired('id');
        $socialId = $request->attributes()->getRequired('nextId');

        $brandSocial = $this->components->orm()->query(Model::BRAND_SOCIAL)
            ->where('brandId', $brandId)
            ->where('socialId', $socialId)
            ->findOne();

        if (!$brandSocial)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('BrandSocial not found');
        }

        $brandSocial->delete();

        return $brandSocial->asObject(true);
    }

    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function headingPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.brandheading.add'))
        {
            throw new \Exception('Access denied');
        }

        $brandId   = $request->attributes()->getRequired('id');
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

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($brandId)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('Brand not found');
        }

        $brandHeading = $this->components->orm()->query(Model::BRAND_HEADING)
            ->where('brandId', $brandId)
            ->where('headingId', $headingId)
            ->findOne();

        if (!$brandHeading)
        {
            $brandHeading            = $this->components->orm()->createEntity(Model::BRAND_HEADING);
            $brandHeading->brandId   = $brandId;
            $brandHeading->headingId = $headingId;
            $brandHeading->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $brandHeading->asObject(true);
    }

    /**
     * @api               {get} /soc/brand/<id>/heading Brand Heading List
     * @apiName           Brand Heading List
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id        brandId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     * @apiParam        {String[]}  [fields] fields
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     * @param Request $request
     *
     * @return array
     */
    public function headingGetAction(Request $request)
    {
        $brandId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($brandId)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setError('brand');
            throw new \InvalidArgumentException('Brand not found');
        }

        $headingQuery = $brand->headings->query();

        $this->query($headingQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $headingQuery, $limit, $preload);

        return $this->pager($pager);
    }

    /**
     * @api               {get} /soc/brand/<id>/heading/<nextId> Brand Heading Item
     * @apiName           Brand Heading Item
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id            brandId
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
        $brandHeadingId = $request->attributes()->getRequired('nextId');

        $preload = $request->query()->get('preload', []);

        try
        {
            $brandHeading = $this->components->orm()->query(Model::BRAND_HEADING)
                ->in($brandHeadingId)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$brandHeading)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('BrandHeading not found');
        }

        return $brandHeading->asObject(true);
    }

    public function headingItemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.brandheading.delete'))
        {
            throw new \Exception('Access denied');
        }

        $brandId = $request->attributes()->getRequired('id');
        $heading = $request->attributes()->getRequired('nextId');

        $brandHeading = $this->components->orm()->query(Model::BRAND_HEADING)
            ->where('brandId', $brandId)
            ->where('headingId', $heading)
            ->findOne();

        if (!$brandHeading)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('BrandHeading not found');
        }

        $brandHeading->delete();

        return $brandHeading->asObject(true);
    }


    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function dealerPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.branddealer.add'))
        {
            throw new \Exception('Access denied');
        }

        $brandId  = $request->attributes()->getRequired('id');
        $dealerId = $request->data()->getRequired('id');

        $dealerIdValidate = filter_var($dealerId, FILTER_VALIDATE_INT);

        if (!$dealerIdValidate)
        {
            throw new \InvalidArgumentException('not a valid Dealer Id');
        }

        $dealer = $this->components->orm()->query(Model::DEALER)
            ->in($dealerId)
            ->findOne();

        if (!$dealer)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('Dealer not found');
        }

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($brandId)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('Brand not found');
        }

        $brandDealer = $this->components->orm()->query(Model::BRAND_DEALER)
            ->where('brandId', $brandId)
            ->where('dealerId', $dealerId)
            ->findOne();

        if (!$brandDealer)
        {
            $brandDealer           = $this->components->orm()->createEntity(Model::BRAND_DEALER);
            $brandDealer->brandId  = $brandId;
            $brandDealer->dealerId = $dealerId;
            $brandDealer->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $brandDealer->asObject(true);
    }

    /**
     * @api               {get} /soc/brand/<id>/dealer Brand Dealer List
     * @apiName           Brand Dealer List
     * @apiGroup          SOC
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.4
     *
     * @apiParam        {Number}  id        dealerId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     * @param Request $request
     *
     * @return array
     */
    public function dealerGetAction(Request $request)
    {
        $brandId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);

        $brand = $this->components->orm()->query(Model::BRAND)
            ->in($brandId)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setError('brand');
            throw new \InvalidArgumentException('Brand not found');
        }

        $dealerQuery = $brand->dealers->query();

        $this->query($dealerQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $dealerQuery, $limit, $preload);

        return $this->pager($pager);
    }

    public function dealerItemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.soc.branddealer.delete'))
        {
            throw new \Exception('Access denied');
        }

        $brandId  = $request->attributes()->getRequired('id');
        $dealerId = $request->attributes()->getRequired('nextId');

        $brandDealer = $this->components->orm()->query(Model::BRAND_DEALER)
            ->where('brandId', $brandId)
            ->where('dealerId', $dealerId)
            ->findOne();

        if (!$brandDealer)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('BrandDealer not found');
        }

        $brandDealer->delete();

        return $brandDealer->asObject(true);
    }

    /**
     * @api               {get} /soc/brand/<id>/address Brand Address List
     * @apiName           Brand Address List
     * @apiGroup          SOW
     *
     * @apiPermission     client user
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.5
     *
     * @apiParam        {Number}  id        brandId
     *
     * @apiParam        {String[]}  [preload] loading relationships
     * @apiParam        {String[]}  [fields] fields
     *
     * @apiParam        {String[]}  [sort] order by id desc
     * @apiParam        {String[]}  [terms] filter equal id = 4
     * @apiParam        {String[]}  [queries] filter LIKE %4%
     *
     * @param Request $request
     *
     * @return array
     */
    public function addressGetAction(Request $request)
    {
        $brandId = $request->attributes()->getRequired('id');

        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page  = $request->query()->get('page', 1);
        $limit = $request->query()->get('limit', 50);

        $preload = $request->query()->get('preload', []);
//        $fields  = $request->query()->get('fields');

        $brand = $this->components->orm()
            ->query(Model::BRAND)
            ->in($brandId)
            ->findOne();

        if (!$brand)
        {
            RESTFUL::setError('brand');
            throw new \InvalidArgumentException('Brand not found');
        }

        $imageQuery = $brand->addresses->query();

        $this->query($imageQuery, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $imageQuery, $limit, $preload);

        return $this->pager($pager);
    }

}