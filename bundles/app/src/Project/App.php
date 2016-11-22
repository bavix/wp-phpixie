<?php

namespace Project;

use PHPixie\DefaultBundle;
use Project\App\Builder;

/**
 * Default application bundle
 *
 * @var $builder Builder
 */
class App extends DefaultBundle
{

    /**
     * Build bundle builder
     *
     * @param \PHPixie\BundleFramework\Builder $frameworkBuilder
     *
     * @return App\Builder
     */
    protected function buildBuilder($frameworkBuilder)
    {
        return new App\Builder($frameworkBuilder);
    }

    /**
     * Authorization helper
     *
     * @return \PHPixie\Auth
     */
    public function auth()
    {
        return $this->builder->components()->auth();
    }

}