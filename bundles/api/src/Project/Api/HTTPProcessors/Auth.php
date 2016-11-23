<?php

namespace Project\Api\HTTPProcessors;

use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use PHPixie\HTTP\Request;

class Auth extends Processor
{

    protected function globalsRequest()
    {
        return \OAuth2\Request::createFromGlobals();
    }

    protected function server()
    {
        $storage = new \Project\OAuth2\PDO($this->builder);

        $server = new \OAuth2\Server($storage, [
            'access_lifetime' => 86400 * 365.25 * 3
        ]);

        $server->addGrantType(new ClientCredentials($storage));
        $server->addGrantType(new AuthorizationCode($storage));
        $server->addGrantType(new RefreshToken($storage));
        $server->addGrantType(new UserCredentials($storage));

        return $server;
    }

    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function callbackAction(Request $request)
    {
        return $request->data()->get();
    }

    public function authorizeAction()
    {
        $this->server();
    }

    /**
     * http -a testC:testS -f POST wbs-cms/api/auth/token grant_type=password username=$USER$ password=$PASSWORD$
     *
     * @return mixed|string
     * @throws \OAuth2\InvalidArgumentException
     * @throws \OAuth2\LogicException
     */
    public function tokenAction()
    {
        return $this->server()
            ->handleTokenRequest($this->globalsRequest())
            ->getResponseBody();
    }

    /**
     * http -f POST wbs-cms/api/auth/resource access_token=$TOKEN$
     *
     * @return array|mixed|string
     */
    public function resourceAction()
    {
        $server         = $this->server();
        $globalsRequest = $this->globalsRequest();

        if (!$server->verifyResourceRequest($globalsRequest))
        {
            /**
             * @var $response \OAuth2\Response
             */
            $response = $server->getResponse();

            return $response->getResponseBody();
        }

        return array(
            'access_token' => $server->getAccessTokenData($globalsRequest),
            'success'      => true,
            'message'      => 'You accessed my APIs!'
        );
    }

}