<?php

namespace Project\Api\HTTPProcessors\SOW;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\HTTPProcessors\Processor\SOWProtected;
use Project\Api\RESTFUL;
use Project\Model;

class Style extends SOWProtected
{

    // default
    public function defaultPostAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.style.add'))
        {
            throw new \Exception('Access denied');
        }

        $type = $request->data()->getRequired('type');
        $type = trim($type);

        if (empty($type))
        {
            throw new \Exception('Type is empty');
        }

        $number = $request->data()->getRequired('number');
        $number = trim($number);

        if (empty($number))
        {
            throw new \Exception('Number is empty');
        }

        $spoke = $request->data()->getRequired('spoke');
        $spoke = trim($spoke);

        if (!is_numeric($spoke))
        {
            throw new \Exception('Spoke is not number');
        }

        $isTurned = $request->data()->get('isTurned', 'off');
        $isTurned = (int)filter_var($isTurned, FILTER_VALIDATE_BOOLEAN);

        $orm = $this->components->orm();

        /**
         * @var \PHPixie\ORM\Wrappers\Type\Database\Query $style
         */
        $style = $orm->query(Model::STYLE);
        $style->where('type', $type);
        $style->where('number', $number);
        $style->where('spoke', $spoke);
        $style->where('isTurned', $isTurned);

        $style = $style->findOne();

        if (!$style)
        {
            $style = $orm->createEntity(Model::STYLE);

            $style->type     = $type;
            $style->number   = $number;
            $style->spoke    = $spoke;
            $style->isTurned = $isTurned;
            $style->save();

            RESTFUL::setStatus(REST::CREATED);
        }

        return $style->asObject(true);
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

        $style = $this->components->orm()->query(Model::STYLE);

        $this->query($style, $request);

        $pager = $builder
            ->helper()
            ->pager($page, $style, $limit, $preload);

        return $pager->getCurrentItems()->asArray(true);
    }


    public function itemDeleteAction(Request $request)
    {
        if (!$this->loggedUser()->hasPermission('cp.sow.style.delete'))
        {
            throw new \Exception('Access denied');
        }

        $id = $request->attributes()->getRequired('id');

        $style = $this->components->orm()->query(Model::STYLE)
            ->in($id)
            ->findOne();

        if (!$style)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        $style->delete();

        return $style->asObject(true);
//        else if ($this->loggedUser()->hasPermission('cp.soc.brand.pull-request'))
//        {
//            // pull request
//
//            return [];
//        }

    }

    public function itemGetAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        $preload = $request->query()->get('preload', []);

        try
        {
            $style = $this->components->orm()->query(Model::STYLE)
                ->in($id)
                ->findOne($preload);
        }
        catch (\Throwable $throwable)
        {
            throw new \InvalidArgumentException('Invalid argument');
        }

        if (!$style)
        {
            RESTFUL::setStatus(REST::NOT_FOUND);

            return [];
        }

        return $style->asObject(true);
    }

}