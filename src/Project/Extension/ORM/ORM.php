<?php

namespace Project\Extension\ORM;

class ORM extends \PHPixie\ORM
{

    /**
     * @var null|\Project\Framework\Components
     */
    protected $components;

    /**
     * ORM constructor.
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
     * @param \PHPixie\Database                         $database
     * @param \PHPixie\Slice\Type\ArrayData             $configSlice
     * @param \PHPixie\ORM\Wrappers\Implementation|null $wrappers
     *
     * @return \PHPixie\ORM\Builder
     */
    protected function buildBuilder($database, $configSlice, $wrappers)
    {
        return new Builder($database, $configSlice, $wrappers, $this->components);
    }

}