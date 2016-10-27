<?php

namespace Project;

use Project\App\Builder;
use \PHPixie\DefaultBundle;

/**
 * Default application bundle
 */
class App extends DefaultBundle
{

    /**
     * @var Builder
     */
    protected $builder;

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