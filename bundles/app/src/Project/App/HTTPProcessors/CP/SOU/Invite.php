<?php

namespace Project\App\HTTPProcessors\CP\SOU;

use PHPixie\HTTP\Request;
use Project\App\HTTPProcessors\Processor\SOUProtected;
use Project\App\Model;

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
        $this->variables['title'] = 'New Item';

        if ($request->method() === 'POST')
        {
            $userId = $this->loggedUser()->getRequiredField('id');

            $orm = $this->components->orm();

            $invite = $orm->createEntity(Model::INVITE);

            $data = $request->data();

            $invite->email = $data->get('email');
            $invite->roleId = $data->get('roleId');

            $invite->userId = $userId;

            $factory = $this->builder->randomFactory();

            $generator = $factory->getHighStrengthGenerator();

            $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ+-';

            $invite->token = $generator->generateString(64, $chars);
        }

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