<?php

namespace Project\App\ORM\Menu;

use \PHPixie\ORM\Wrappers\Type\Database\Entity;
use Project\App\Builder;
use Project\App\Model;

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

    public function getProcessor()
    {
        $processors = explode('.', $this->entity->processor);

        return current($processors);
    }

    public function nextProcessor()
    {
        $processors = explode('.', $this->entity->processor);

        return $processors[1] ?? null;
    }

}