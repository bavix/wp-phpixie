<?php

namespace Project\Extension\ORM;

class Drivers extends \PHPixie\ORM\Drivers
{

    /**
     * @type Builder
     */
    protected $ormBuilder;

    /**
     * @var array
     */
    protected $classMap = array(
        'pdo'   => PDO::class,
        'mongo' => '\PHPixie\ORM\Drivers\Driver\Mongo',
    );

    /**
     * @param string $name
     *
     * @return \PHPixie\Database\Driver
     */
    protected function buildDriver($name)
    {

        $class = $this->classMap[$name];

        if ($name === 'pdo')
        {
            return new $class(
                $this->ormBuilder->configs(),
                $this->ormBuilder->conditions(),
                $this->ormBuilder->data(),
                $this->ormBuilder->database(),
                $this->ormBuilder->models(),
                $this->ormBuilder->maps(),
                $this->ormBuilder->mappers(),
                $this->ormBuilder->values(),
                $this->ormBuilder->components
            );
        }

        return parent::buildDriver($name);

    }

}