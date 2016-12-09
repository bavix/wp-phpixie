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

    protected $access = [
        'defaultPost',
        'itemDelete'
    ];

    // default
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

        $brand = $this->query($brand, $request);

        $pager = $builder->helper()->pager($page, $brand, $limit, $preload);

        return $pager->getCurrentItems()->asArray(true);
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

    public function socialGetAction(Request $request)
    {
        $brandId = $request->attributes()->getRequired('id');

        $preload = $request->query()->get('preload', []);
        $fields  = $request->query()->get('fields', []);

        try
        {
            $brandSocial = $this->components->orm()->query(Model::BRAND_SOCIAL)
                ->where('brandId', $brandId)
                ->find($preload, $fields);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        return $brandSocial->asArray(true);
    }

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

        $brandSocialId = $request->attributes()->getRequired('nextId');

        $brandSocial = $this->components->orm()->query(Model::BRAND_SOCIAL)
            ->in($brandSocialId)
            ->findOne();

        if (!$brandSocial)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            throw new \InvalidArgumentException('BrandSocial not found');
        }

        $brandSocial->delete();

        return $brandSocial->asObject(true);
    }

}