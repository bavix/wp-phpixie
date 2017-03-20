<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Model;

class Upload extends AuthProcessor
{

    protected $allow = [
        'brandPost',
        'wheelPost',
        'avatarPost',
    ];

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
    {// fixme : убрать мой быдлокод
        $data = $request->data();

        if ($data->get('status') !== 'ok')
        {
            return [
                'status' => 'error'
            ];
        }

        $id = $data->get('query.id');

        if (!$id)
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

        $logo              = $this->components->orm()->createEntity(Model::IMAGE);
        $logo->hash        = $data->get('hash');
        $logo->size        = $data->get('fileSize');
        $logo->width       = $data->get('sizes.width');
        $logo->height      = $data->get('sizes.height');
        $logo->userId      = $data->get('query.userId');
        $logo->description = $data->get('description');

        $brand = $this->components->orm()->repository(Model::BRAND)->query()
            ->in($id)
            ->findOne();

        if (!$brand)
        {
            return [
                'status' => 'error'
            ];
        }

        // ALTER TABLE `brands` ADD `imageId` INT NOT NULL AFTER `parentId`;

        $logo->itemId = $brand->id();
        $logo->save();

        $brand->imageId = $logo->id();
        $brand->save();

        return ['status' => 'ok'];
    }


    public function avatarPostAction(Request $request)
    {// fixme : убрать мой быдлокод
        $data = $request->data();

        if ($data->get('status') !== 'ok')
        {
            return [
                'status' => 'error'
            ];
        }

        $id = $data->get('query.id');

        if (!$id)
        {
            return [
                'status' => 'error'
            ];
        }

        $logo              = $this->components->orm()->createEntity(Model::IMAGE);
        $logo->hash        = $data->get('hash');
        $logo->size        = $data->get('fileSize');
        $logo->width       = $data->get('sizes.width');
        $logo->height      = $data->get('sizes.height');
        $logo->userId      = $data->get('query.userId');
        $logo->description = $data->get('description');

        $user = $this->components->orm()->repository(Model::USER)->query()
            ->in($id)
            ->findOne();

        if (!$user)
        {
            return [
                'status' => 'error'
            ];
        }

        // ALTER TABLE `brands` ADD `imageId` INT NOT NULL AFTER `parentId`;

        $logo->itemId = $user->id();
        $logo->save();

        $user->imageId = $logo->id();
        $user->save();


        return ['status' => 'ok'];
    }

    public function wheelPostAction(Request $request)
    {// fixme : убрать мой быдлокод
        $data = $request->data();

        if ($data->get('status') !== 'ok')
        {
            return [
                'status' => 'error'
            ];
        }

        $id = $data->get('query.id');

        if (!$id)
        {
            return [
                'status' => 'error'
            ];
        }

        if ($data->get('query.preview', 1))
        {

            $preview = $this->components->orm()->createEntity(Model::IMAGE);

            $preview->hash        = $data->get('hash');
            $preview->size        = $data->get('fileSize');
            $preview->width       = $data->get('sizes.width');
            $preview->height      = $data->get('sizes.height');
            $preview->userId      = $data->get('query.userId');
            $preview->description = $data->get('description');

            $wheel = $this->components->orm()->repository(Model::WHEEL)->query()
                ->in($id)
                ->findOne();

            if (!$wheel)
            {
                return [
                    'status' => 'error'
                ];
            }

            $preview->itemId = $wheel->id();
            $preview->save();

            $wheel->imageId = $preview->id();
            $wheel->save();

        }
        else
        {

            $image = $this->components->orm()->createEntity(Model::IMAGE);

            $image->hash        = $data->get('hash');
            $image->size        = $data->get('fileSize');
            $image->width       = $data->get('sizes.width');
            $image->height      = $data->get('sizes.height');
            $image->userId      = $data->get('query.userId');
            $image->description = $data->get('description');

            $wheel = $this->components->orm()->repository(Model::WHEEL)->query()
                ->in($id)
                ->findOne();

            if (!$wheel)
            {
                return [
                    'status' => 'error'
                ];
            }

            $image->save();

            $wheel->images->add($image);

        }

        return ['status' => 'ok'];
    }

}