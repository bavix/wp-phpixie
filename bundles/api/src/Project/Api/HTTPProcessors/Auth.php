<?php

namespace Project\Api\HTTPProcessors;

use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use PHPixie\HTTP\Request;

class Auth extends AuthProcessor
{

    /**
     * @var array
     */
    protected $access = ['resourcePost'];

    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function callbackGetAction(Request $request)
    {
        return $request->data()->get();
    }

    public function authorizeGetAction()
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
    public function tokenPostAction()
    {
        return $this->server()
            ->handleTokenRequest($this->globalsRequest())
            ->getResponseBody();
    }

    /**
     * http -f POST wbs-cms/api/auth/resource access_token=$TOKEN$
     *
     * @return array|bool|mixed|string
     */
    public function resourcePostAction()
    {
        return ['success'];
    }

}