<?php

namespace Project\ORM\Brand;

use Project\App\Builder;
use Project\ORM\Entity;
use Project\ORM\EntityImage;

class Brand extends Entity
{

    use EntityImage;

    protected $imageType = 'brand';

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * @return string
     */
    public function imageThumbs()
    {
        return $this->_getImage('thumbs', 210, '500x500');
    }

}