<?php

namespace Project\ORM\Brand;

use Deimos\ImaginariumSDK\SDK;
use PHPixie\ORM\Wrappers\Type\Database\Entity;
use Project\App\Builder;

class Brand extends Entity
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
            $uri    = $this->builder->components()->http()->request()->uri();
            $host = $uri->getHost();

            $sdk = new SDK();
            $sdk->setServer('cdn.' . $host);
            $sdk->setUserName('brand');

            return $sdk->getThumbsUrl($type, $logo->hash, $type . '.png');
        }

        return '//placehold.it/' . $size . 'x' . $size . '?text=' . $text;
    }

    /**
     * @return string
     */
    public function thumbsLogo()
    {
        return $this->_logo('thumbs', 210, '500x500');
    }

}