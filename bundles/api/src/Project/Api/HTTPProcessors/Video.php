<?php

namespace Project\Api\HTTPProcessors;

use Embed\Embed;
use PHPixie\HTTP\Request;

class Video extends AuthProcessor
{

    /**
     * @api           {get} /video/embed Video Embed Data
     * @apiName       Embed
     * @apiGroup      Video
     *
     * @apiPermission client user
     *
     * @apiHeader     Authorization Authorization Bearer {access_token}
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

}