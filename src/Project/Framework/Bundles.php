<?php

namespace Project\Framework;

use Project\App;

/**
 * Your projects bundle registry.
 * Every bundle you add must be registered here.
 */
class Bundles extends \PHPixie\BundleFramework\Bundles
{
    /**
     * Should return an array of Bundle instances
     * @return array
     */
    protected function buildBundles()
    {
        return array(
//            new \PHPixie\FrameworkBundle($this->builder),
            new App($this->builder)
        );
    }
}