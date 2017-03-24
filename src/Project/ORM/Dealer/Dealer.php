<?php

namespace Project\ORM\Dealer;

use Project\App\Builder;
use Project\ORM\Entity;
use Project\ORM\EntityImage;

class Dealer extends Entity
{

    use EntityImage;

    protected $imageType = 'dealer';

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