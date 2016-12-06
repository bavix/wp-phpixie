<?php

namespace Project\Api\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Api\HTTPProcessors\Processor\SOCProtected;
use Project\Framework\Builder;
use Project\Model;

class Brand extends SOCProtected
{

    protected $access = ['defaultPost'];

    public function defaultPostAction(Request $request)
    {


        return [__METHOD__];
    }

    public function defaultPatchAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        return [$id, __METHOD__];
    }

    public function defaultDeleteAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        if ($this->user->hasPermission('cp.soc.brand.delete'))
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
        else if ($this->user->hasPermission('cp.soc.brand.pull-request'))
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

        return $pager->getCurrentItems();
    }

}