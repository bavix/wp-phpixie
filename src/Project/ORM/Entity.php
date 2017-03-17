<?php

namespace Project\ORM;

use Deimos\ImaginariumSDK\SDK;

class Entity extends EmptyEntity
{

    protected function defaultImage($width, $height, $text = null)
    {
        return '//placehold.it/' . $width . 'x' . $height . '?text=' . $text;
    }

    /**
     * @param string $type
     * @param int    $size
     * @param string $text
     *
     * @return string
     */
    protected function _logo($type, $size = 210, $text = '1600x1600')
    {
        $logo = $this->image();

        if ($logo)
        {
            $request = $this->builder->components()->http()->request();
            $uri     = $request->uri();
            $host    = $uri->getHost();

            /**
             * @var $uri \PHPixie\HTTP\Messages\URI\SAPI
             */
            $sdk = new SDK();
            $sdk->setServer('cdn.' . $host);
            $sdk->setUserName($this->modelName());

            return $sdk->getThumbsUrl($type, $logo->hash);
        }

        return $this->defaultImage($size, $size, $text);
    }

}