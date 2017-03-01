<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Api\Exceptions\Unauthorized;
use Project\Api\RESTFUL;
use Project\Model;

class Account extends AuthProcessor
{

    /**
     * @api           {get} /account User Account
     * @apiName       Get Current User Info
     * @apiGroup      Account
     *
     * @apiPermission user
     *
     * @apiHeader     Authorization Authorization Bearer {access_token}
     *
     * @apiVersion    0.0.6
     *
     * @param Request $request
     *
     * @return null|\Project\ORM\User\User
     */
    public function defaultGetAction(Request $request)
    {
        $user = $this->loggedUser();

        if ($user)
        {
            return $user->asObject(true);
        }

        throw new Unauthorized();
    }

    /**
     * @api           {post} /account Update User Account
     * @apiName       Update Current User Info
     * @apiGroup      Account
     *
     * @apiPermission user
     *
     * @apiHeader     Authorization Authorization Bearer {access_token}
     *
     * @apiParam      {String} [lastname]   lastname
     * @apiParam      {String} [name]       name (firstname)
     * @apiParam      {String} [email]      e-mail
     * @apiParam      {String} [about]
     *
     * @apiVersion    0.0.6
     *
     * @param Request $request
     */
    public function defaultPostAction(Request $request)
    {
//        $user = $this->loggedUser();
        $user = $this->components->orm()->query(Model::USER)->findOne();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new \InvalidArgumentException('User not found');
        }

        $lastname = $request->data()->get('lastname');

        if (!empty($lastname))
        {
            $user->lastname = $lastname;
        }

        $name = $request->data()->get('name');

        if (!empty($name))
        {
            $user->name = $name;
        }

        $email = $request->data()->get('email');

        if (!empty($email))
        {
            $user->email = $email;
        }

        $about = $request->data()->get('about');

        if (!empty($about))
        {
            $user->about = $about;
        }

        $user->save();

        return $user->asObject(true);
    }

}