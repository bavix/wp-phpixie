<?php

namespace Project\ORM\Wheel;

use Deimos\ImaginariumSDK\SDK;
use PHPixie\ORM\Wrappers\Type\Database\Entity;
use Project\App\Builder;

class Wheel extends Entity
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
        $logo = $this->previewWheel();

        if ($logo)
        {
            $uri    = $this->builder->components()->http()->request()->uri();
            $host = $uri->getHost();

            $sdk = new SDK();
            $sdk->setServer('cdn.' . $host);
            $sdk->setUserName('wheel');

            return $sdk->getThumbsUrl($type, $logo->hash, $type . '.png');
        }

        return '//placehold.it/' . $size . 'x' . $size . '?text=' . $text;
    }

    /**
     * @return string
     */
    public function thumbsLogo()
    {
        return $this->_logo('thumbs', 210);
    }

}