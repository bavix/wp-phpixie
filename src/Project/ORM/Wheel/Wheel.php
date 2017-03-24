<?php

namespace Project\ORM\Wheel;

use Project\App\Builder;
use Project\ORM\Entity;
use Project\ORM\EntityImage;

class Wheel extends Entity
{

    use EntityImage;
    
    protected $imageType = 'wheel';

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * @return string
     */
    public function imageThumbs()
    {
        return $this->_getImage('thumbs', 210);
    }

}