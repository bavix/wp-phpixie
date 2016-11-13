<?php

namespace Project\App\HTTPProcessors\CP\SOU;

use Carbon\Carbon;
use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\SOUProtected;
use Project\App\Model;
use Project\App\Role;

class Invite extends SOUProtected
{

    /**
     * @param Request $request
     */
    public function defaultAction(Request $request)
    {
        return $this->render('app:cp/sou/invite/default');
    }

    public function addAction(Request $request)
    {
        $data = $request->data();

        $inviteSystem = false;
        $email        = $data->get('email');
        $roleId       = $data->get('roleId', Role::User);

        if ($request->method() === 'POST')
        {
            $orm = $this->components->orm();

            $invite = $orm->query(Model::INVITE)
                ->where('email', $email)
                ->findOne();

            $user = $orm->query(Model::USER)
                ->where('email', $email)
                ->findOne();

            if ($user)
            {
                // error
            }

            if ($invite)
            {
                // error
            }

            $invite = $orm->createEntity(Model::INVITE);

            $invite->email  = $email;
            $invite->roleId = $roleId;

            $userId         = $this->loggedUser()->getRequiredField('id');
            $invite->userId = $userId;

            $factory = $this->builder->randomFactory();

            $generator = $factory->getHighStrengthGenerator();

            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $invite->token = $generator->generateString(64, $chars);

            $carbon = Carbon::create();
            $carbon->addDay(3); // add 3 day

            $invite->expires = $carbon->timestamp;

            return $invite->asObject(true);
        }

        $roles = $this->components->orm()->query(Model::ROLE)
            ->find()
            ->asArray(true);

        $this->variables['email']  = $email;
        $this->variables['roleId'] = $roleId;

        $this->variables['inviteSystem'] = $inviteSystem;
        $this->variables['roles']        = $roles;

        $this->variables['title'] = 'New Item';

        return $this->render('app:cp/sou/invite/add');
    }

//    public function registerAction(Request $request)
//    {
//        $token = $request->query()->getRequired('token');
//
//        $orm = $this->components->orm();
//
//        if ($request->method() === 'POST')
//        {
//
//            $invite = $orm->query(Model::INVITE)
//                ->where('token', $token)
//                ->findOne();
//
//            // validate
//
//        }
//
//        return $this->render('app:cp/sou/invite/register');
//    }

}