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

        $message = null;

        if ($request->method() === 'POST')
        {
            $orm = $this->components->orm();

            $user = $orm->query(Model::USER)
                ->where('email', $email)
                ->findOne();

            $invite = $orm->query(Model::INVITE)
                ->where('email', $email)
                ->where('expires', '>', time())
                ->where('activated', '=', 0)
                ->findOne();

            if ($user)
            {
                $message['alert-danger'] = 'The user with such email is already registered!';
            }
            else if ($invite)
            {
                $message['alert-danger'] = 'Invite has been sent to the specified email earlier, we expect activation.';
            }
            else
            {
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

                if ($invite->save())
                {
                    $config = $this->builder->bundleConfig();

                    /**
                     * @var $mailConfig \PHPixie\Slice\Type\Slice\Editable
                     */
                    $mailConfig = $config->slice('mail');

                    $smtp     = $mailConfig->get('smtp');
                    $username = $mailConfig->get('username');
                    $password = $mailConfig->get('password');

                    $transport = \Swift_SmtpTransport::newInstance($smtp);
                    $transport->setUsername($username);
                    $transport->setPassword($password);

                    $template = $this->template->render('app:email/invite', array(
                        'invite' => $invite
                    ));

                    $mailMessage = \Swift_Message::newInstance()
                        ->setFrom([$username => 'Invite Bot'])
                        ->setTo([$email])
                        ->setSubject('Invite from WBS CMS')
                        ->setBody($template, 'text/html', 'utf-8');

                    $mailer = \Swift_Mailer::newInstance($transport);

                    $message['alert-danger'] = 'Error of sending invite. Try later!';

                    if ($mailer->send($mailMessage))
                    {
                        $message['alert-success'] = 'Invite is successfully sent to the user!';
                    }
                }

            }
        }

        $roles = $this->components->orm()->query(Model::ROLE)
            ->find()
            ->asArray(true);

        $this->variables['message'] = $message;

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