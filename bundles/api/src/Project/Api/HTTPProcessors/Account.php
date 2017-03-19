<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Api\Exceptions\Unauthorized;
use Project\Api\RESTFUL;
use Project\Model;

class Account extends AuthProcessor
{

    /**
     * @api               {get} /account User Account
     * @apiName           Get Current User Info
     * @apiGroup          Account
     *
     * @apiPermission     user
     *
     * @apiHeader         Authorization Bearer {access_token}
     *
     * @apiVersion        0.0.6
     *
     * @apiSuccessExample Success-Response:
     *                    HTTP/1.1 200 OK
     *                    {
     *                      "id": 1,
     *                      "googleId": null,
     *                      "facebookId": null,
     *                      "instagramId": null,
     *                      "githubId": "123456789",
     *                      "vkId": "123456789",
     *                      "twitterId": null,
     *                      "dropboxId": null,
     *                      "imageId": null,
     *                      "login": "rez1dent3",
     *                      "lastname": "Бабичев",
     *                      "name": "Максим",
     *                      "email": "maksim.babichev95@gmail.com",
     *                      "about": null,
     *                      "passwordHash": "PASSWORD",
     *                      "roleId": 1,
     *                      "createdAt": "2016-10-06 17:45:45",
     *                      "updatedAt": "2016-11-02 04:30:30"
     *                     }
     *
     * @param Request $request
     *
     * @return null|\Project\ORM\User\User
     */
    public function defaultGetAction(Request $request)
    {
        $user    = $this->loggedUser();
        $preload = $request->query()->get('preload', []);

        if ($user)
        {
            // fixme: for preload
            $user = $this->components->orm()->query(Model::USER)
                ->in($user)
                ->findOne($preload);

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
     * @apiHeader     Authorization Bearer {access_token}
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
        $user = $this->loggedUser();
//        $user = $this->components->orm()->query(Model::USER)->findOne();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $lastname = $request->data()->get('lastname');

        if (!empty($lastname) && $lastname !== $user->lastname)
        {
            $user->lastname = $lastname;
        }

        $name = $request->data()->get('name');

        if (!empty($name) && $name !== $user->name)
        {
            $user->name = $name;
        }

        $email = $request->data()->get('email');

        if (!empty($email) && $email !== $user->email)
        {
            $check = $this->components->orm()->query(Model::USER)
                ->where('email', $email)
                ->findOne();

            if ($check)
            {
                RESTFUL::setError('email');
                throw new \InvalidArgumentException('Email exists');
            }

            $user->email = $email;
        }

        $about = $request->data()->get('about');

        if (!empty($about) && $about !== $user->about)
        {
            $user->about = $about;
        }

        $user->save();

        return $user->asObject(true);
    }

    /**
     * @api           {post} /account/image Update User Avatar
     * @apiName       Update Current User Avatar
     * @apiGroup      Account
     *
     * @apiPermission user
     *
     * @apiHeader     Authorization Bearer {access_token}
     *
     * @apiParam      {file} filedata Image file
     *
     * @apiVersion    0.0.7
     *
     * @param Request $request
     */
    public function imagePostAction(Request $request)
    {
        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();
        $helper  = $builder->dHelper();
        $temp    = $this->builder->webRoot();

        $simple = $helper->uploads()->simple('filedata');

        $name = md5($simple->tmpName());
        $to   = $temp->path($name);

        $simple->save($to);

        $host = $request->uri()->getHost();

        if (0 === strpos($host, 'api.'))
        {
            $host = substr($host, 4);
        }

        $data = $helper->send()
            ->to('http://cdn.' . $host . '/api/upload/avatar?id=' . $user->id() . '&userId=' . $user->id())
            ->file('filedata', $to)
            ->headers([
                'Origin: *'
            ])
            ->exec();

        @unlink($to);

        return $data;
    }

    /**
     * @api           {delete} /account/image Delete User Avatar
     * @apiName       Delete Current User Avatar
     * @apiGroup      Account
     *
     * @apiPermission user
     *
     * @apiHeader     Authorization Bearer {access_token}
     *
     * @apiVersion    0.0.8
     *
     * @param Request $request
     */
    public function imageDeleteAction(Request $request)
    {
        $user = $this->loggedUser();

        if (!$user)
        {
            RESTFUL::setError('user');
            throw new Unauthorized();
        }

        $user->imageId = null;
        if ($user->save())
        {
            RESTFUL::setStatus(REST::NO_CONTENT);

            return $user->asObject(true);
        }

        RESTFUL::setError('avatar');
        throw new \InvalidArgumentException('Can\'t delete avatar');
    }

}