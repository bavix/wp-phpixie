<?php

namespace Project\ORM;

use Project\App\Builder;

class Entity extends \PHPixie\ORM\Wrappers\Type\Database\Entity
{

    protected $imageType;

    /**
     * @var $builder Builder
     */
    protected $builder;

    /**
     * Brand constructor.
     *
     * @param $entity
     * @param $builder
     */
    public function __construct($entity, $builder)
    {
        parent::__construct($entity);
        $this->builder = $builder;
    }

}