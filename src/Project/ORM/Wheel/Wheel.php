<?php

namespace Project\ORM\Wheel;

use Project\App\Builder;
use Project\ORM\Entity;

class Wheel extends Entity
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
        return $this->_getImage('thumbs', 210);
    }

}