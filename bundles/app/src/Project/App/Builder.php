<?php

namespace Project\App;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use PHPixie\DefaultBundle\Builder as DefaultBuilder;
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
     * @return Logger
     */
    public function log()
    {
        return $this->instance('log');
    }

    /**
     * @return Logger
     */
    protected function buildLog()
    {
        $path = $this->webRoot()->path('log.log');

        $handler = new StreamHandler($path, Logger::WARNING);

        $log = new Logger($this->bundleName());
        $log->pushHandler($handler);

        return $log;
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