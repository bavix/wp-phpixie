<?php

namespace Project\Api\HTTPProcessors;

use Embed\Embed;
use PHPixie\HTTP\Request;
use Project\Model;

class Video extends AuthProcessor
{

    /**
     * @api           {get} /video/embed Video Embed Data
     * @apiName       Embed
     * @apiGroup      Video
     *
     * @apiPermission client user
     *
     * @apiHeader     Authorization Bearer {access_token}
     *
     * @apiParam      url example: https://www.youtube.com/watch?v=x3bLrSVxpzg
     *
     * @apiVersion    0.0.5
     *
     * @return array
     */
    public function embedGetAction(Request $request)
    {
        $url = $request->query()->getRequired('url');

        $info = Embed::create($url);

        return [
            'url'      => $info->url,
            'provider' => $info->providerName,

            'title'       => $info->title,
            'description' => $info->description,

            'image'       => $info->image,
            'imageWidth'  => $info->imageWidth,
            'imageHeight' => $info->imageHeight,

            'width'       => $info->width,
            'height'      => $info->height,
            'aspectRatio' => $info->aspectRatio,

            'authorName' => $info->authorName,
            'authorUrl'  => $info->authorUrl,
        ];
    }

//    protected $allow = ['debugGet'];
//
//    public function debugGetAction()
//    {
//
//        // brands
//        $storage = $this->components->orm()->query('brandLogo')->find();
//
//        foreach ($storage as $item)
//        {
//            if (!$item->brandId)
//            {
//                $brand = $this->components->orm()->query(Model::BRAND)
//                    ->where('imageId', $item->id)
//                    ->findOne();
//
//                if (!$brand)
//                    continue;
//            }
//            else {
//
//                $brand = $this->components->orm()->query(Model::BRAND)->in($item->brandId)->findOne();
//            }
//
//            $image = $this->components->orm()->createEntity(Model::IMAGE);
//
//            $image->description = $item->description;
//            $image->hash        = $item->hash;
//            $image->itemId      = $brand->id();
//            $image->userId      = $item->userId;
//            $image->size        = $item->size;
//            $image->width       = $item->width;
//            $image->height      = $item->height;
//
//            $image->save();
//            $brand->image->set($image);
//        }
//
//        // wheels
//        $storage = $this->components->orm()->query('previewWheel')->find();
//
//        foreach ($storage as $item)
//        {
//            if (!$item->wheelId)
//            {
//                $wheel = $this->components->orm()->query(Model::WHEEL)
//                    ->where('imageId', $item->id)
//                    ->findOne();
//
//                if (!$wheel)
//                    continue;
//            }
//            else {
//
//                $wheel = $this->components->orm()->query(Model::WHEEL)->in($item->wheelId)->findOne();
//            }
//
//            $image = $this->components->orm()->createEntity(Model::IMAGE);
//
//            $image->description = $item->description;
//            $image->hash        = $item->hash;
//            $image->itemId      = $wheel->id();
//            $image->userId      = $item->userId;
//            $image->size        = $item->size;
//            $image->width       = $item->width;
//            $image->height      = $item->height;
//
//            $image->save();
//            $wheel->image->set($image);
//        }
//
//    }

}