<?php

namespace Project\ORM\Brand;

use Project\App\Builder;
use Project\ORM\Entity;

class Brand extends Entity
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * @return string
     */
    public function thumbsLogo()
    {
        return $this->_getImage('thumbs', 210, '500x500');
    }

}