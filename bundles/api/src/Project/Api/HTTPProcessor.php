<?php

namespace Project\Api;

use PHPixie\HTTP\Request;

class HTTPProcessor extends \PHPixie\DefaultBundle\Processor\HTTP\Builder
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var \PHPixie\Database\Driver\PDO
     */
    protected $connection;

    /**
     * @var
     */
    protected $storage;
    protected $server;
    protected $config;
    protected $authorizationCode;

    /**
     * Constructor
     *
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;

        $this->connection = $this->builder->components()->database()->driver('pdo');

        echo $this->server();
        die;
    }

    protected function pdo()
    {
        return new \PDO('mysql:host=localhost;dbname=temp', 'root', '');
    }

    protected function config()
    {
        if (!$this->config)
        {
            $this->config = array(
                'client_table'        => 'clients',
                'access_token_table'  => 'accessTokens',
                'refresh_token_table' => 'refreshTokens',
                'code_table'          => 'authorizationCodes',
                'user_table'          => 'users',
                'jwt_table'           => 'jwt',
                'jti_table'           => 'jti',
                'scope_table'         => 'scopes',
                'public_key_table'    => 'publicKeys',
            );
        }

        return $this->config;
    }

    protected function storage()
    {
        if (!$this->storage)
        {
            $pdo    = $this->pdo();
            $config = $this->config();

            $this->storage = new \OAuth2\Storage\Pdo($pdo, $config);
        }

        return $this->storage;
    }

    protected function server()
    {
        if (!$this->server)
        {
            $storage = $this->storage();

            $this->server = new \OAuth2\Server($storage);

            // todo
            $authorizationCode = $this->authorizationCode();
            $this->server->addGrantType($authorizationCode);

            $tokenRequest = \OAuth2\Request::createFromGlobals();

            return $this->server->handleTokenRequest($tokenRequest)
                ->send();
        }

        return $this->server;
    }

    protected function authorizationCode()
    {
        if (!$this->authorizationCode)
        {
            $storage = $this->storage();

            $this->authorizationCode = new \OAuth2\GrantType\AuthorizationCode($storage);
        }

        return $this->authorizationCode;
    }

}