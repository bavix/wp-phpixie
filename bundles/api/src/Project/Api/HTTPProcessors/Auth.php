<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Model;

class Auth extends AuthProcessor
{

    /**
     * @var array
     */
    protected $access = [
        'resourcePost',
        'registerPost',
    ];

    /**
     * @param Request $request
     *
     * @return array|null|string
     */
    public function callbackGetAction(Request $request)
    {
        return $request->data()->get();
    }

    /**
     * @api {post} /auth/resource Test Request
     * @apiName Test
     * @apiGroup Auth
     *
     * @apiHeader Authorization: Basic access_token
     *
     * @return array
     */
    public function resourcePostAction(Request $request)
    {
        $user = $this->loggedUser();

        return [
            'type' => 'success',
            'post' => $request->data()->get(),
            'user' => $user ? $user->asObject(true) : null,
        ];
    }

    /**
     * @return null|\Project\ORM\User\User
     * @throws \PHPixie\ORM\Exception\Query
     */
    public function loggedUser()
    {
        if (!$this->user)
        {
            if ($this->server()->verifyResourceRequest($this->globalsRequest()))
            {
                $this->server()->verifyResourceRequest($this->globalsRequest());
                $accessToken = $this->server()->getAccessTokenData($this->globalsRequest());

                if ($accessToken['user_id'])
                {
                    $this->user = $this->components->orm()->query(Model::USER)
                        ->in($accessToken['user_id'])
                        ->findOne();

                    $this->components->auth()->domain()->setUser(
                        $this->loggedUser(),
                        'default'
                    );
                }

            }
        }

        return parent::loggedUser();
    }

    public function registerPostAction(Request $request)
    {

    }

    /**
     * @api {post} /auth/token Test Request
     * @apiName Test
     * @apiGroup Auth
     *
     * @apiParam grant_type=password|client_credentials
     * @apiParam username=LOGIN
     * @apiParam password=PASSWORD
     *
     * @apiHeader Authorization: Basic access_token
     *
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

}