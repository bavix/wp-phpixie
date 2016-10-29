<?php

namespace Project\App;

use PHPixie\DefaultBundle\Builder as DefaultBuilder;
use Project\App\HTTPProcessors\Admin\Auth;
use Stash\Pool;

/**
 * App bundle builder
 */
class Builder extends DefaultBuilder
{

    /**
     * @return Pool
     */
    public function cache()
    {
        return $this->instance('cache');
    }

    /**
     * @return Pool
     */
    protected function buildCache()
    {
        $driver = $this
            ->bundleConfig()
            ->get('cache.driver', \Stash\Driver\Apc::class);

        return new Pool(new $driver());
    }

    /**
     * Build Processor for HTTP requests
     *
     * @return HTTPProcessor
     */
    protected function buildHttpProcessor()
    {
        return new HTTPProcessor($this);
    }

    /**
     * Build ORM Wrappers
     *
     * @return ORMWrappers
     */
    protected function buildORMWrappers()
    {
        return new ORMWrappers($this);
    }

    /**
     * @return AuthRepositories
     */
    protected function buildAuthRepositories()
    {
        return new AuthRepositories(
            $this->components()->orm()
        );
    }

    /**
     * Get bundle root directory
     *
     * @return string
     */
    protected function getRootDirectory()
    {
        return realpath(__DIR__ . '/../../../');
    }

    /**
     * Get bundle name
     *
     * @return string
     */
    public function bundleName()
    {
        return 'app';
    }
}