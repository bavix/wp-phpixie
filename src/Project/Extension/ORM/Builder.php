<?php

namespace Project\Extension\ORM;

class Builder extends \PHPixie\ORM\Builder
{

    /**
     * @var null|\Project\Framework\Components
     */
    public $components;

    /**
     * Builder constructor.
     *
     * @param \PHPixie\Database                         $database
     * @param \PHPixie\Slice\Type\ArrayData             $configSlice
     * @param \PHPixie\ORM\Wrappers\Implementation|null $wrappers
     * @param \Project\Framework\Components|null        $components
     */
    public function __construct($database, $configSlice, $wrappers = null, $components = null)
    {
        $this->components = $components;

        parent::__construct($database, $configSlice, $wrappers);
    }

    /**
     * @return Drivers
     */
    protected function buildDrivers()
    {
        return new Drivers($this);
    }

}