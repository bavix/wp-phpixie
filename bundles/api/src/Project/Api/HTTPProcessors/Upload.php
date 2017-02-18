<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Framework\Builder;
use Project\Model;

class Upload extends AuthProcessor
{

//    public function toPostAction(Request $request)
//    {
//        /**
//         * @var $builder Builder
//         */
//        $builder = $this->builder->frameworkBuilder();
//
//        $to = $request->uri()->getScheme() . '://';
//        $to .= 'cdn.' . $request->uri()->getHost() . '/api/upload/';
//        $to .= $request->query()->get('type') . '?';
//        $to .= http_build_query($request->query()->get());
//
//        $fileData = $builder->dHelper()->uploads()->simple('filedata');
//
//        return $builder->dHelper()->send()
//            ->data($request->data()->get())
//            ->to($to)
//            ->file('filedata', $fileData->tmpName())
//            ->method('POST')
//            ->exec();
//    }

    public function brandPostAction(Request $request)
    {
        $data = $request->data();

        if ($data->get('status') !== 'ok')
        {
            return [
                'status' => 'error'
            ];
        }

//        fileSize          // bytes
//        sizes.width       // width
//        sizes.height      // height
//        mime              // mime
//        hash              // hash
//        user              // type == brand
//        query.id          // brandId
//        query.userId      // userId
//        data.Filename     // file name

        return $data->get();

        $logo              = $this->components->orm()->createEntity(Model::BRAND_LOGO);
        $logo->hash        = $data->get('hash');
        $logo->size        = $data->get('fileSize');
        $logo->width       = $data->get('sizes.width');
        $logo->height      = $data->get('sizes.height');
        $logo->brandId     = $data->get('query.id');
        $logo->userId      = $data->get('query.userId');
        $logo->description = $data->get('description');

        $brand = $this->components->orm()->repository(Model::BRAND)->query()
            ->in($data->get('query.id'))
            ->findOne();

        if (!$brand)
        {
            return [
                'status' => 'error'
            ];
        }

        // ALTER TABLE `brands` ADD `imageId` INT NOT NULL AFTER `parentId`;

        $logo->save();

        $brand->brandLogo->set($logo);

        return ['status' => 'ok'];
    }

}