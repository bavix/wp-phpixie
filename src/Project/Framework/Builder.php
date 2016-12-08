<?php

namespace Project\Framework;

use Project\Curl;
use Project\Helper;
use RandomLib\Factory;
use Stash\Driver\Composite;
use Stash\Pool;

/**
 * Your projects main factory, usually referenced as $frameworkBuilder.
 *
 * You can use it to override and customize the framework.
 */
class Builder extends \PHPixie\BundleFramework\Builder
{

    /**
     * @return Helper
     */
    public function helper()
    {
        return $this->instance('helper');
    }

    /**
     * @return Helper
     */
    protected function buildHelper()
    {
        return new Helper($this);
    }

    /**
     * @return Curl
     */
    public function curl()
    {
        return $this->instance('curl');
    }

    /**
     * @return Curl
     *
     * @throws \ErrorException
     */
    protected function buildCurl()
    {
        return new Curl($this);
    }

    /**
     * @return Factory
     */
    public function randomFactory()
    {
        return $this->instance('randomFactory');
    }

    /**
     * @return Factory
     */
    protected function buildRandomFactory()
    {
        return new Factory();
    }

    /**
     * @return Pool
     */
    public function cache()
    {
        return $this->instance('cache');
    }

    /**
     * @return Pool
     * @throws \Exception
     */
    protected function buildCache()
    {
        $config = $this->assets()->configStorage();

        /**
         * @var $drivers array
         */
        $drivers = $config->get('cache.drivers');

        $subDrivers = [];

        foreach ($drivers as $driver => $options)
        {
            if (!is_array($options))
            {
                throw new \Exception();
            }

            /**
             * @var $subDriver \Stash\Driver\AbstractDriver
             */
            $subDriver = new $driver($options);

            $subDrivers[] = $subDriver;
        }

        $options = array('drivers' => $subDrivers);
        $driver  = new Composite($options);

        return new Pool($driver);
    }

    /**
     * Your Bundles registry
     *
     * @return Bundles
     */
    protected function buildBundles()
    {
        return new Bundles($this);
    }

    /**
     * @return Components
     */
    protected function buildComponents()
    {
        return new Components($this);
    }

    /**
     * Your extension registry registry
     *
     * @return Extensions
     */
    protected function buildExtensions()
    {
        return new Extensions($this);
    }

    /**
     * Projects root directory
     *
     * @return Bundles
     */
    protected function getRootDirectory()
    {
        return dirname(__DIR__, 3);
    }

}