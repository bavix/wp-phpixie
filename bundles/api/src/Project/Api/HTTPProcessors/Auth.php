<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Model;
use Project\ORM\User\Query;
use Project\Role;

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
     * @api        {post} /auth/resource Test Request
     * @apiName    Test
     * @apiGroup   Auth
     *
     * @apiPermission client user
     *
     * @apiHeader  Authorization Authorization Basic {access_token}
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
     * @api        {post} /auth/register Register
     * @apiName    Register
     * @apiGroup   Auth
     *
     * @apiPermission client
     *
     * @apiParam   grant_type value client_credentials
     * @apiParam   username login
     * @apiParam   email email
     * @apiParam   password password
     *
     * @apiHeader  Authorization Authorization Basic {access_token}
     *
     * @apiSuccessExample Success-Response:
     *                    HTTP/1.1 200 OK
     *                    {
     *                      "name": "Maxim",
     *                      "lastName": "Babichev",
     *                      "login": "rez1dent3",
     *                      "roleId": "1"
     *                    }
     *
     * @apiErrorExample Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "error": "username",
     *                      "error_description": "The username is empty"
     *                  }
     *
     * @apiVersion 0.0.2
     */
    public function registerPostAction(Request $request)
    {

        $username = $request->data()->getRequired('username');

        if (empty($username))
        {
            $this->error = 'username';
            throw new \InvalidArgumentException('The username is empty');
        }

        $email = $request->data()->getRequired('email');

        if (empty($email))
        {
            $this->error = 'email';
            throw new \InvalidArgumentException('The email is empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            $this->error = 'email';
            throw new \InvalidArgumentException('Not valid email');
        }

        $password = $request->data()->getRequired('password');

        if (empty($password))
        {
            $this->error = 'password';
            throw new \InvalidArgumentException('The password is empty');
        }

        if (mb_strlen($password) < 6)
        {
            $this->error = 'password';
            throw new \InvalidArgumentException('The password is less than 6 symbols');
        }

        $orm = $this->builder->components()->orm();

        /**
         * @var $queryUser Query
         */
        $queryUser = $orm->query(Model::USER);

        $this->user = $queryUser->findByLogin($username);

        if ($this->user)
        {
            $this->error = 'username';
            throw new \InvalidArgumentException('username is already used');
        }

        $this->user = $queryUser->findByEmail($username);

        if ($this->user)
        {
            $this->error = 'email';
            throw new \InvalidArgumentException('email is already used');
        }

        $this->user = $orm->createEntity(Model::USER);

        $domain = $this->builder->components()->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        $passwordHash = $passwordProvider->hash($password);

        $this->user->login    = $username;
        $this->user->email    = $email;
        $this->user->password = $passwordHash;
        $this->user->roleId   = Role::Register;

        $this->user->save();

        return $this->user->asObject(true);
    }

    /**
     * http -a testC:testS -f POST wbs-cms/api/auth/token grant_type=password username=$USER$ password=$PASSWORD$
     *
     * @api        {post} /auth/token Get Token
     * @apiName    Token
     * @apiGroup   Auth
     *
     * @apiPermission none
     *
     * @apiParam   grant_type value password OR client_credentials
     * @apiParam   username LOGIN
     * @apiParam   password PASSWORD
     *
     * @apiHeader  Authorization Authorization Basic {access_token}
     *
     * @apiSuccessExample Success-Response:
     *                    HTTP/1.1 200 OK
     *                    Content-Type: application/json;charset=UTF-8
     *                    Cache-Control: no-store
     *                    Pragma: no-cache
     *                    {
     *                      "access_token": "2YotnFZFEjr1zCsicMWpAA",
     *                      "token_type": "example",
     *                      "expires_in": 3600,
     *                      "refresh_token": "tGzv3JOkF0XG5Qx2TlKWIA",
     *                      "example_parameter": "example_value"
     *                    }
     *
     * @apiErrorExample Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "error": "invalid_client",
     *                      "error_description": "The client credentials are invalid"
     *                  }
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