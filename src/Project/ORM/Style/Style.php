<?php

namespace Project\ORM\Style;

use Project\ORM\Entity;

class Style extends Entity
{

    public function __toString()
    {
        return $this->type . ' ' . $this->number . ' ' . $this->spoke . ' ' . ( $this->isTurned ? 'R' : '' );
    }

}