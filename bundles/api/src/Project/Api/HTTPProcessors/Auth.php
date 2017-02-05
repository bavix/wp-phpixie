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
     * @apiHeader Authorization Authorization Basic {access_token}
     *
     * @apiVersion 0.0.1
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

    /**
     * @api {post} /auth/register Register
     * @apiName Register
     * @apiGroup Auth
     *
     * @apiParam grant_type=client_credentials
     *
     * @apiHeader Authorization Authorization Basic {access_token}
     *
     * @apiVersion 0.0.2
     */
    public function registerPostAction(Request $request)
    {

    }

    /**
     * http -a testC:testS -f POST wbs-cms/api/auth/token grant_type=password username=$USER$ password=$PASSWORD$
     *
     * @api {post} /auth/token Get Token
     * @apiName Token
     * @apiGroup Auth
     *
     * @apiParam grant_type=password|client_credentials
     * @apiParam username=LOGIN
     * @apiParam password=PASSWORD
     *
     * @apiHeader Authorization Authorization Basic {access_token}
     *
     * @apiVersion 0.0.1
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