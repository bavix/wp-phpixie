<?php

namespace Project\ORM;

use Deimos\ImaginariumSDK\SDK;

trait EntityImage
{

    protected function defaultImage($width, $height, $text = null)
    {
        return '//placehold.it/' . $width . 'x' . $height . '?text=' . $text;
    }

    protected function setImageType($type)
    {
        $this->imageType = $type;

        return $this;
    }

    protected function _getImageObject()
    {
        return $this->imageType ? (
            $this instanceof Image\Image ? $this : null
        ) : $this->image();
    }

    /**
     * @param string $type
     * @param int    $size
     * @param string $text
     *
     * @return string
     */
    protected function _getImage($type, $size = 210, $text = '1600x1600')
    {
        $imageObject = $this->_getImageObject();

        if ($imageObject)
        {
            $request = $this->builder->components()->http()->request();
            $uri     = $request->uri();
            $host    = $uri->getHost();

            /**
             * @var $uri \PHPixie\HTTP\Messages\URI\SAPI
             */
            $sdk = new SDK();
            $sdk->setServer('cdn.' . 'wheelpro.ru'); //$host);
            $sdk->setUserName($this->imageType ?: $this->modelName());

            var_dump($imageObject->getField('hash'), $imageObject);
            die;

            return $sdk->getThumbsUrl($type, $imageObject->hash);
        }

        return $this->defaultImage($size, $size, $text);
    }

}