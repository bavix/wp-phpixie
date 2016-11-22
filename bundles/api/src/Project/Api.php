<?php

namespace Project;

/**
 * Default application bundle
 */
class Api extends \PHPixie\DefaultBundle
{

    /**
     * Build bundle builder
     *
     * @param \PHPixie\BundleFramework\Builder $frameworkBuilder
     *
     * @return Api\Builder
     */
    protected function buildBuilder($frameworkBuilder)
    {
        return new Api\Builder($frameworkBuilder);
    }

}