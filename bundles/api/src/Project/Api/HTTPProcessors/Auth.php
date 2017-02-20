<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\RESTFUL;
use Project\Model;
use Project\ORM\User\Query;
use Project\ORM\User\User;
use Project\Role;

class Auth extends AuthProcessor
{

    /**
     * @var array
     */
    protected $access = [
        'resourcePost',
        'registerPost',
        'recoveryPasswordPost',
        'newPasswordPost',
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
     * @api           {post} /auth/resource Test Request
     * @apiName       Test
     * @apiGroup      Auth
     *
     * @apiPermission client user
     *
     * @apiHeader     Authorization Authorization Bearer {access_token}
     *
     * @apiVersion    0.0.1
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
     * @api               {post} /auth/register Register
     * @apiName           Register
     * @apiGroup          Auth
     *
     * @apiPermission     client
     *
     * @apiParam         {String} username login
     * @apiParam         {String} email email
     * @apiParam         {String{6..}} password password
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
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
     * @apiErrorExample   Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "error": "username",
     *                      "error_description": "The username is empty"
     *                  }
     *
     * @apiVersion        0.0.2
     */
    public function registerPostAction(Request $request)
    {

        $username = $request->data()->getRequired('username');

        if (empty($username))
        {
            RESTFUL::setError('username');
            throw new \InvalidArgumentException('The username is empty');
        }

        if (preg_match('~[^\w.]~', $username))
        {
            RESTFUL::setError('username');
            throw new \InvalidArgumentException('Username has to contain only a-z, 0-9, _.');
        }

        $email = $request->data()->getRequired('email');

        if (empty($email))
        {
            RESTFUL::setError('email');
            throw new \InvalidArgumentException('The email is empty');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            RESTFUL::setError('email');
            throw new \InvalidArgumentException('Not valid email');
        }

        $password = $request->data()->getRequired('password');

        if (empty($password))
        {
            RESTFUL::setError('password');
            throw new \InvalidArgumentException('The password is empty');
        }

        if (mb_strlen($password) < 6)
        {
            RESTFUL::setError('password');
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
            RESTFUL::setError('username');
            throw new \InvalidArgumentException('username is already used');
        }

        $this->user = $queryUser->findByEmail($username);

        if ($this->user)
        {
            RESTFUL::setError('email');
            throw new \InvalidArgumentException('email is already used');
        }

        $this->user = $orm->createEntity(Model::USER);

        $domain = $this->builder->components()->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        $passwordHash = $passwordProvider->hash($password);

        $this->user->login        = $username;
        $this->user->email        = $email;
        $this->user->passwordHash = $passwordHash;
        $this->user->roleId       = Role::Register;

        $this->user->save();

        return $this->user->asObject(true);
    }

    /**
     * @api               {post} /auth/recovery-password Recovery Password
     * @apiName           Recovery Password
     * @apiGroup          Auth
     *
     * @apiPermission     none
     *
     * @apiParam         {String} email EMAIL
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiSuccessExample Success-Response:
     *                    HTTP/1.1 200 OK
     *                    Content-Type: application/json;charset=UTF-8
     *                    Cache-Control: no-store
     *                    Pragma: no-cache
     *                    {
     *                      "isSend": true
     *                    }
     *
     * @apiErrorExample   Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "isSend": false
     *                  }
     *
     * @apiVersion        0.0.3
     *
     * @param Request $request
     */
    public function recoveryPasswordPostAction(Request $request)
    {
        $email = $request->data()->getRequired('email');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            RESTFUL::setError('email');
            throw new \InvalidArgumentException('Not valid email');
        }

        $orm = $this->components->orm();

        /**
         * @var Query $userQuery
         */
        $userQuery = $orm->query(Model::USER);

        /**
         * @var User $user
         */
        $user = $userQuery->findByEmail($email);

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        $isSend = $user->recoveryPassword($this->template) > 0;

        if (!$isSend)
        {
            RESTFUL::setStatus(REST::BAD_REQUEST);
        }

        return [
            'isSend' => $isSend
        ];
    }

    /**
     * @api               {post} /auth/change-password Change Password
     * @apiName           Change Password
     * @apiGroup          Auth
     *
     * @apiPermission     none
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiParam         {String} oldPassword old Password (user)
     * @apiParam         {String} password new Password (user)
     *
     * @apiSuccessExample Success-Response:
     *                    HTTP/1.1 200 OK
     *                    Content-Type: application/json;charset=UTF-8
     *                    Cache-Control: no-store
     *                    Pragma: no-cache
     *                    {
     *                      "isUpdate": true
     *                    }
     *
     * @apiErrorExample   Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "error": "password",
     *                      "error_description": "The password is empty",
     *                  }
     *
     * @apiErrorExample   Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "isUpdate": false
     *                  }
     *
     * @apiVersion        0.0.4
     *
     * @param Request $request
     */
    public function changePasswordPostAction(Request $request)
    {

        $oldPassword    = $request->data()->getRequired('oldPassword');
        $password = $request->data()->getRequired('password');

        if (empty($oldPassword))
        {
            RESTFUL::setError('oldPassword');
            throw new \InvalidArgumentException('The old password is empty');
        }

        if (mb_strlen($oldPassword) < 6)
        {
            RESTFUL::setError('oldPassword');
            throw new \InvalidArgumentException('The old password is less than 6 symbols');
        }

        if (empty($password))
        {
            RESTFUL::setError('password');
            throw new \InvalidArgumentException('The password is empty');
        }

        if (mb_strlen($password) < 6)
        {
            RESTFUL::setError('password');
            throw new \InvalidArgumentException('The password is less than 6 symbols');
        }


        if (!$this->user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        // todo
        throw new \InvalidArgumentException('Action in developing');

        $domain = $this->builder->components()->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        var_dump( get_class($passwordProvider) );

        $passwordHash = $passwordProvider->hash($password);

        $this->user->passwordHash = $passwordHash;

        $update = $this->user->save();

        if (!$update)
        {
            RESTFUL::setStatus(REST::BAD_REQUEST);
        }

        return [
            'isUpdate' => (bool)$update
        ];

    }

    /**
     * @api               {post} /auth/new-password New Password
     * @apiName           New Password
     * @apiGroup          Auth
     *
     * @apiPermission     none
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
     *
     * @apiParam         {String} email EMAIL
     * @apiParam         {String} code code [from email] use recovery-password
     * @apiParam         {String} password new Password (user)
     *
     * @apiSuccessExample Success-Response:
     *                    HTTP/1.1 200 OK
     *                    Content-Type: application/json;charset=UTF-8
     *                    Cache-Control: no-store
     *                    Pragma: no-cache
     *                    {
     *                      "isUpdate": true
     *                    }
     *
     * @apiErrorExample   Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "error": "password",
     *                      "error_description": "The password is empty",
     *                  }
     *
     * @apiErrorExample   Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "isUpdate": false
     *                  }
     *
     * @apiVersion        0.0.3
     *
     * @param Request $request
     */
    public function newPasswordPostAction(Request $request)
    {
        $email    = $request->data()->getRequired('email');
        $code     = $request->data()->getRequired('code');
        $password = $request->data()->getRequired('password');

        if (empty($password))
        {
            RESTFUL::setError('password');
            throw new \InvalidArgumentException('The password is empty');
        }

        if (mb_strlen($password) < 6)
        {
            RESTFUL::setError('password');
            throw new \InvalidArgumentException('The password is less than 6 symbols');
        }

        $orm = $this->components->orm();

        /**
         * @var $userQuery Query
         */
        $userQuery = $orm->query(Model::USER);
        $user      = $userQuery->findByEmail($email);

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        $recovery = $orm->query(Model::RECOVERY_PASSWORD)
            ->where('userId', $user->id())
            ->where('code', $code)
            ->where('expires', '>=', time())
            ->where('active', 1)
            ->findOne();

        if (!$recovery)
        {
            $recovery = $orm->query(Model::RECOVERY_PASSWORD)
                ->where('userId', $user->id())
                ->where('active', 1)
                ->findOne();

            if (!$recovery)
            {
                RESTFUL::setError('code');
                throw new \InvalidArgumentException('The user has no confidential codes');
            }

            if (++$recovery->try > 2)
            {
                $recovery->active = 0;
            }

            $recovery->save();

            RESTFUL::setError('code');
            throw new \InvalidArgumentException('Recovery code not found');
        }

        $domain = $this->builder->components()->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        $passwordHash = $passwordProvider->hash($password);

        $user->passwordHash = $passwordHash;

        $update = $user->save();

        if (!$update)
        {
            RESTFUL::setStatus(REST::BAD_REQUEST);
        }

        return [
            'isUpdate' => (bool)$update
        ];

    }

    /**
     * http -a testC:testS -f POST wbs-cms/api/auth/token grant_type=password username=$USER$ password=$PASSWORD$
     *
     * @api               {post} /auth/token Get Token
     * @apiName           Token
     * @apiGroup          Auth
     *
     * @apiPermission     none
     *
     * @apiParam         {String} grant_type value password OR client_credentials
     * @apiParam         {String} username LOGIN
     * @apiParam         {String} password PASSWORD
     *
     * @apiHeader         Authorization Authorization Bearer {access_token}
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
     * @apiErrorExample   Error-Response:
     *                  HTTP/1.1 400 Bad Request
     *                  {
     *                      "error": "invalid_client",
     *                      "error_description": "The client credentials are invalid"
     *                  }
     *
     * @apiVersion        0.0.1
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