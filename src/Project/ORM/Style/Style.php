<?php

namespace Project\ORM\Style;

use Project\ORM\EmptyEntity;

class Style extends EmptyEntity
{

    public function __toString()
    {
        return $this->type . ' ' . $this->number . ' ' . $this->spoke . ' ' . ( $this->isTurned ? 'R' : '' );
    }

}