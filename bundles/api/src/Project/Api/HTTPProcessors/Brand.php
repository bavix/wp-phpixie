<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Framework\Builder;
use Project\Model;

class Brand extends AuthProcessor
{

    protected $access = ['defaultGet'];

    public function defaultGetAction(Request $request)
    {
        /**
         * @var $builder Builder
         */
        $builder = $this->builder->frameworkBuilder();

        $page = $request->query()->get('page', 1);

        $brand = $this->components->orm()->query(Model::BRAND);

        $pager = $builder->helper()->pager($page, $brand);

        return $pager->getCurrentItems();
    }

}