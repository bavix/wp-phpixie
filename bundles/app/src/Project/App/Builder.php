<?php

namespace Project\App;

use PHPixie\DefaultBundle\Builder as DefaultBuilder;

/**
 * App bundle builder
 */
class Builder extends DefaultBuilder
{

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