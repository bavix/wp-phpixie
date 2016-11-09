<?php

namespace Project\App\ORM\Menu;

use \PHPixie\ORM\Wrappers\Type\Database\Entity;
use Project\App\Builder;
use Project\App\Model;
use Project\Util;

class Menu extends Entity
{

    /**
     * @var $builder Builder
     */
    protected $builder;

    public function __construct($entity, $builder)
    {
        parent::__construct($entity);
        $this->builder = $builder;
    }

    public function httpPath()
    {
        return Util::httpWithURL($this->entity->httpPath);
    }

}