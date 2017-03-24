<?php

namespace Project\ORM\Wheel;

use Project\App\Builder;
use Project\ORM\EntityImage;

class Wheel extends EntityImage
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * @return string
     */
    public function thumbsImage()
    {
        return $this->_getImage('thumbs', 210);
    }

}