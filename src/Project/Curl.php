<?php

namespace Project;

use Project\Framework\Builder;

class Curl extends \Curl\Curl
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Helper constructor.
     *
     * @param $builder Builder
     *
     * @throws \ErrorException
     */
    public function __construct($builder)
    {
        parent::__construct();

        $this->builder = $builder;
    }

    /**
     * @param $path
     *
     * @return string
     */
    protected function url($path)
    {
        $url   = $path;
        $parse = parse_url($path, PHP_URL_HOST);

        if (empty($parse))
        {
            $uri = $this->builder->components()->http()->request()->uri();
            $url = $uri->getScheme() . '://' . $uri->getHost() . '/';

            $url .= ltrim($path, '/');
        }

        return $url;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setBasicAuth($name = 'default')
    {
        $config = $this->builder->assets()->configStorage()
            ->slice('oauth.' . $name);

        $clientId     = $config->get('clientId');
        $clientSecret = $config->get('clientSecret');

        return $this->setBasicAuthentication($clientId, $clientSecret);
    }

    /**
     * @param       $url
     * @param array $data
     *
     * @return $this
     */
    public function get($url, $data = array())
    {
        parent::get($this->url($url), $data);

        return $this;
    }

    /**
     * @param       $url
     * @param array $data
     *
     * @return $this
     */
    public function post($url, $data = array())
    {
        parent::post($this->url($url), $data);

        return $this;
    }

    /**
     * @param       $url
     * @param array $data
     * @param bool  $payload
     *
     * @return $this
     */
    public function put($url, $data = array(), $payload = false)
    {
        parent::put($this->url($url), $data, $payload);

        return $this;
    }

    /**
     * @param       $url
     * @param array $data
     * @param bool  $payload
     *
     * @return $this
     */
    public function patch($url, $data = array(), $payload = false)
    {
        parent::patch($this->url($url), $data, $payload);

        return $this;
    }

    /**
     * @param       $url
     * @param array $data
     * @param bool  $payload
     *
     * @return $this
     */
    public function delete($url, $data = array(), $payload = false)
    {
        parent::delete($this->url($url), $data, $payload);

        return $this;
    }

    /**
     * @param $username
     * @param $password
     *
     * @return $this
     */
    public function setBasicAuthentication($username, $password)
    {
        parent::setBasicAuthentication($username, $password);

        return $this;
    }

    /**
     * @param $httpAuth
     *
     * @return $this
     */
    protected function setHttpAuth($httpAuth)
    {
        parent::setHttpAuth($httpAuth);

        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setHeader($key, $value)
    {
        parent::setHeader($key, $value);

        return $this;
    }

    /**
     * @param $userAgent
     *
     * @return $this
     */
    public function setUserAgent($userAgent)
    {
        parent::setUserAgent($userAgent);

        return $this;
    }

    /**
     * @param $referrer
     *
     * @return $this
     */
    public function setReferrer($referrer)
    {
        parent::setReferrer($referrer);

        return $this;
    }

    /**
     * @param $key
     * @param $value
     *
     * @return $this
     */
    public function setCookie($key, $value)
    {
        parent::setCookie($key, $value);

        return $this;
    }

    /**
     * @param $option
     * @param $value
     *
     * @return $this
     */
    public function setOpt($option, $value)
    {
        parent::setOpt($option, $value);

        return $this;
    }

    /**
     * @param bool $on
     *
     * @return $this
     */
    public function verbose($on = true)
    {
        parent::verbose($on);

        return $this;
    }

}