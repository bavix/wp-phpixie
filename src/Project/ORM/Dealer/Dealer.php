<?php

namespace Project\ORM\Dealer;

use Project\App\Builder;
use Project\ORM\Entity;

class Dealer extends Entity
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
        return $this->_logo('thumbs', 210, '500x500');
    }

}