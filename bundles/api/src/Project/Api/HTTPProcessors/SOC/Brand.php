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

    public function itemDeleteAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        if ($this->loggedUser()->hasPermission('cp.soc.brand.delete'))
        {

            $brand = $this->components->orm()->query(Model::BRAND)
                ->in($id)
                ->findOne();

            $brand->delete();

            return [
                'status' => 'success',
                'data'   => [
                    'isDeleted'   => $brand->isDeleted(),
                    'pullRequest' => false
                ]
            ];

        }
        else if ($this->loggedUser()->hasPermission('cp.soc.brand.pull-request'))
        {
            // pull request

            return [
                'status' => 'error',
                'data'   => [
                    'isDeleted'   => false,
                    'pullRequest' => false
                ]
            ];
        }

        throw new \Exception();
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

        $brand = $this->components->orm()->query(Model::BRAND);

        $pager = $builder->helper()->pager($page, $brand, $limit);

        return $pager->getCurrentItems()->asArray(true);
    }

}