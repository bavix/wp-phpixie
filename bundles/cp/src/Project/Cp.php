<?php

namespace Project;

/**
 * Default application bundle
 */
class Cp extends \PHPixie\DefaultBundle
{

    /**
     * Build bundle builder
     *
     * @param \PHPixie\BundleFramework\Builder $frameworkBuilder
     *
     * @return Cp\Builder
     */
    protected function buildBuilder($frameworkBuilder)
    {
        return new Cp\Builder($frameworkBuilder);
    }

}