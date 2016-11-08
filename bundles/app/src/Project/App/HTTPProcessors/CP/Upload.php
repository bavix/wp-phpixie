<?php

namespace Project\App\HTTPProcessors\CP;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\CPProtected;
use Project\App\Model;

class Upload extends CPProtected
{

    const METHOD_IMAGE = 'image';
    const METHOD_VIDEO = 'video';

    /**
     * @param string $method
     *
     * @return array
     */
    public function upload($method = self::METHOD_IMAGE)
    {

        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
//        header("Access-Control-Allow-Origin: *");

        set_time_limit(5 * 60); // 5 minutes execution time

        $http = $this->components->http();

        $request = $http->request();

        if (!$this->user->hasPermission('cp.upload.' . $method))
        {
            return [];
        }

        $filename = uniqid();
        $hash     = hash('sha256', $filename);
        $split    = str_split($hash, 8);

        $files = $request->uploads()->get('file');

        return [
            'filename' => $filename,
            'hash'     => $hash,
            'path'     => array_merge([$method], $split),
            'files'    => $files,
            '_FILES'   => $_FILES,
            'r'        => mt_rand(1, 111111)
        ];

    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function imageAction($request)
    {
        $storage = $this->upload(self::METHOD_IMAGE);

        if (!empty($storage))
        {
            // my code
        }

        return $storage;
    }

    /**
     * @param Request $request
     *
     * @return array
     */
    public function videoAction($request)
    {
        $storage = $this->upload(self::METHOD_VIDEO);

        if (!empty($storage))
        {
            // my code
        }

        return $storage;
    }

}