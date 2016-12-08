<?php

namespace Project\Api\HTTPProcessors;

use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\Exceptions\Unauthorized;
use Project\Api\RESTFUL;
use Project\Extension\Util;

class AuthProcessor extends Processor
{

    /**
     * @var array
     */
    protected $access = [];

    /**
     * @return \OAuth2\Request
     */
    protected function globalsRequest()
    {
        return \OAuth2\Request::createFromGlobals();
    }

    /**
     * @return \OAuth2\Server
     */
    protected function server()
    {
        $storage = new \Project\OAuth2\PDO($this->builder);

        $server = new \OAuth2\Server($storage, [
            'access_lifetime' => 94672800 // 3 year
        ]);

        $server->addGrantType(new ClientCredentials($storage));
        $server->addGrantType(new AuthorizationCode($storage));
        $server->addGrantType(new RefreshToken($storage));
        $server->addGrantType(new UserCredentials($storage));

        return $server;
    }

    /**
     * @return bool|mixed|string
     */
    protected function accessDenied()
    {
        $server         = $this->server();
        $globalsRequest = $this->globalsRequest();

        if (!$this->loggedUser() && !$server->verifyResourceRequest($globalsRequest))
        {
//            /**
//             * @var $response \OAuth2\Response
//             */
//            $response = $server->getResponse();
//
//            return $response->getResponseBody();

            RESTFUL::setStatus(REST::UNAUTHORIZED);

            throw new Unauthorized();
        }

        return false;
    }

    /**
     * @param Request $httpRequest
     *
     * @return string
     */
    public function getActionNameFor($httpRequest)
    {
        $method = $httpRequest->method();
        $method = strtolower($method);
        $method = ucfirst($method);

        $action = $httpRequest->attributes()->get('action');

        if ($httpRequest->attributes()->get('nextId'))
        {
            $action .= '-item';
        }

        return Util::camelCase($action) . $method;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \PHPixie\Processors\Exception
     */
    public function process($request)
    {
        $method = $request->method();
        $method = strtolower($method);
        $method = ucfirst($method);

        $action = $request->attributes()->get('action');

        if (in_array($action . $method, $this->access, true))
        {
            $accessDenied = $this->accessDenied();

            if ($accessDenied)
            {
                return $accessDenied;
            }
        }

        return parent::process($request);
    }

    /**
     * @param \PHPixie\ORM\Wrappers\Type\Database\Query $query
     * @param Request                                   $request
     */
    public function query($query, Request $request)
    {

        /**
         * [ [ name, sort ], [ id, sort ] ]
         *
         * @var array $order
         */
        $orders = $request->query()->get('orders', []);

        foreach ($orders as $order)
        {

            list($field, $type) = $order;

            if ($type === 'desc')
            {
                $query->orderDescendingBy($field);
            }
            else
            {
                $query->orderAscendingBy($field);
            }

        }

        /**
         * // default
         *
         * [ [ column, operator, value ], [ column, operator, value ] ]
         *
         * @var array $terms
         */

        $terms = $request->query()->get('terms', []);

        foreach ($terms as $term)
        {
            list ($column, $operator, $value) = $term;

            $query->where($column, $operator, $value);
        }

        return $query;

    }

}