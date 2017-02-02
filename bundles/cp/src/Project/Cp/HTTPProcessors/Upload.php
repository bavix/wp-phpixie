<?php

namespace Project\Cp\HTTPProcessors;

use Deimos\ImaginariumSDK\SDK;
use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\CPProtected;

/**
 * Admin upload
 */
class Upload extends CPProtected
{

    /**
     * Dashboard page
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function imageAction(Request $request)
    {
        try
        {
            $type = $request->data()->getRequired('type');

            $sdk = new SDK();
            $sdk->setBasedir(dirname(__DIR_WEB__) . '/uploads');
            $sdk->setUserName($type);

            // current server-name
            $sdk->setServer('storage.' . $request->uri()->getHost());

            /**
             * @var \PHPixie\HTTP\Messages\UploadedFile\SAPI $file
             */
            $file = $request->uploads()->get('file');

            /**
             * @var \Project\Framework\Builder $builder
             */
            $builder = $this->builder->frameworkBuilder();

            $hash = $builder->dHelper()->str()->random(6);

            $path = $sdk->getOriginalPath($hash);

            $builder->dHelper()->dir()->make(dirname($path));

            $file->moveTo($path);

            return [
                'hash' => $hash,
                'size' => $file->getSize(),
                'url'  => $sdk->getOriginalUrl($hash)
            ];

        }
        catch (\Throwable $exception)
        {
            return [
                'message' => $exception->getMessage()
            ];
        }
    }

}