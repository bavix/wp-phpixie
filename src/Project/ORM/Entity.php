<?php

namespace Project\ORM;

use Deimos\ImaginariumSDK\SDK;
use PHPixie\ORM\Wrappers\Type\Database\Entity as PHPixieEntity;
use Project\App\Builder;

class Entity extends PHPixieEntity
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * Brand constructor.
     *
     * @param $entity
     * @param $builder
     */
    public function __construct($entity, $builder)
    {
        parent::__construct($entity);
        $this->builder = $builder;
    }

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
            $uri  = $this->builder->components()->http()->request()->uri();
            $host = $uri->getHost();

            $sdk = new SDK();
            $sdk->setServer('cdn.' . $host, $uri->getScheme());
            $sdk->setUserName($this->modelName());

            return $sdk->getThumbsUrl($type, $logo->hash, $type . '.png');
        }

        return $this->defaultImage($size, $size, $text);
    }

}