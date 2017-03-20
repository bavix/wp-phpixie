<?php

namespace Project\ORM\Image;

use Project\App\Builder;
use Project\ORM\Entity;

class Image extends Entity
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * @return string
     */
    public function thumbs($type)
    {
        return $this
            ->setImageType($type)
            ->_getImage(__FUNCTION__, 210, '250x250');
    }

    /**
     * @return string
     */
    public function optimize($type)
    {
        return $this
            ->setImageType($type)->
            _getImage(__FUNCTION__, 500, '500x500');
    }

    /**
     * @return string
     */
    public function normal($type)
    {
        return $this
            ->setImageType($type)
            ->_getImage(__FUNCTION__, 1000, '1000x1000');
    }

    /**
     * @return string
     */
    public function maximal($type)
    {
        return $this
            ->setImageType($type)
            ->_getImage(__FUNCTION__, 1600, '1600x1600');
    }

}