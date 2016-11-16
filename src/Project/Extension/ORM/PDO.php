<?php

namespace Project\Extension\ORM;

class PDO extends \PHPixie\ORM\Drivers\Driver\PDO
{
    protected $components;

    public function __construct($configs, $conditions, $data, $database, $models, $maps, $mappers, $values, $components)
    {
        $this->components = $components;

        parent::__construct($configs, $conditions, $data, $database, $models, $maps, $mappers, $values);
    }

    public function repository($config)
    {
        return new PDO\Repository(
            $this->models->database(),
            $this->database,
            $this->data,
            $config,
            $this->components
        );
    }
}