<?php

namespace Project\Cp\HTTPProcessors\SOC;

use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOCProtected;
use Project\Model;

class Brand extends SOCProtected
{

    public function addAction(Request $request)
    {
        if ($request->method() === 'POST')
        {
            $title = $request->data()->getRequired('title');

            $title = mb_strtoupper($title);

            if (!empty($title))
            {
                $orm = $this->components->orm();

                $brand = $orm->query(Model::BRAND)
                    ->where('title', $title)
                    ->findOne();

                if (!$brand)
                {
                    $brand = $orm->createEntity(Model::BRAND);

                    $brand->title = $title;
                    $brand->save();
                }

                $resolverPath = 'cp.soc.brand@edit.' . $brand->id();

                return $this->redirectWithUtil($resolverPath);
            }
        }

        return $this->render('cp:soc/brand/add');
    }

    public function editAction(Request $request)
    {
        $id = $request->attributes()->getRequired('id');

        return $this->render('cp:soc/brand/edit');
    }

    public function deleteAction(Request $request)
    {
        // ajax
        return [];
    }

    public function defaultAction(Request $request)
    {
        return $this->render('cp:soc/brand/default');
    }

}