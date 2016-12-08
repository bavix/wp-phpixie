<?php

namespace Project\Framework;

use PHPixie\FrameworkBundle;
use Project\Api;
use Project\App;
use Project\Cp;

/**
 * Your projects bundle registry.
 * Every bundle you add must be registered here.
 */
class Bundles extends \PHPixie\BundleFramework\Bundles
{

    /**
     * Should return an array of Bundle instances
     *
     * @return array
     */
    protected function buildBundles()
    {
        return array(
            new FrameworkBundle($this->builder),
            new Api($this->builder),
            new Cp($this->builder),
            new App($this->builder),
        );
    }

}