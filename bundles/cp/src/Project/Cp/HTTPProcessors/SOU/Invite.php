<?php

namespace Project\Cp\HTTPProcessors\SOU;

use Carbon\Carbon;
use Openbuildings\Swiftmailer\CssInlinerPlugin;
use PHPixie\HTTP\Request;
use Project\Cp\HTTPProcessors\Processor\SOUProtected;
use Project\Extension\Mail;
use Project\Model;
use Project\Role;

class Invite extends SOUProtected
{

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \PHPixie\Paginate\Exception
     */
    public function defaultAction(Request $request)
    {
        $this->addItemButton('cp.sou.invite@add');

        /**
         * @var $builder \Project\Framework\Builder
         */
        $builder = $this->builder->frameworkBuilder();
        $helper  = $builder->helper();

        $orm = $this->components->orm();

        $page = $request->query()->get('page');

        $queryInvite = $orm->query(Model::INVITE)
            ->orderDescendingBy('createdAt');

        $pager = $helper->pager($page, $queryInvite);

        $this->assign('pager', $pager);

        return $this->render('cp:sou/invite/default');
    }

    public function addAction(Request $request)
    {
        $data = $request->data();

        $inviteSystem = false;
        $email        = $data->get('email');
        $roleId       = $data->get('roleId', Role::User);
        // validator add
        $message = null;

        if ($request->method() === 'POST')
        {
            $orm = $this->components->orm();

            /**
             * @var $user \Project\ORM\User\Query
             */
            $user = $orm->query(Model::USER);
            $user = $user->findByEmail($email);

            /**
             * @var $invite \Project\ORM\Invite\Query
             */
            $invite = $orm->query(Model::INVITE);
            $invite = $invite->findByEmail($email);

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
                /**
                 * @var \Project\Framework\Builder $builder
                 */
                $builder = $this->builder->frameworkBuilder();

                $invite = $orm->createEntity(Model::INVITE);

                $invite->email  = $email;
                $invite->roleId = $roleId;

                $userId         = $this->loggedUser()->getRequiredField('id');
                $invite->userId = $userId;

                $dHelper = $builder->dHelper();

                $invite->token = $dHelper->str()->random(8);

                $carbon = Carbon::create();
                $carbon->addDay(3); // add 3 day

                $invite->expires = $carbon->timestamp;

                if ($invite->save())
                {
                    $logoPath = __DIR_WEB__ . 'svg/wheel-white.png';
                    $logoData = file_get_contents($logoPath);
                    $logoData = 'data:image/png;base64,' . base64_encode($logoData);

                    $template = $this->template->render('app:email/invite', array(
                        'assetsPath' => __DIR_WEB__ . 'assets/',
                        'cssPath'    => __DIR_WEB__ . 'css/',
                        'jsPath'     => __DIR_WEB__ . 'js/',
                        'logo'       => $logoData,
                        'scheme'     => $request->uri()->getScheme(),
                        'host'       => $request->uri()->getHost(),
                        'invite'     => $invite
                    ));

                    $send = $builder->helper()->mail(Mail::TYPE_INVITE)
                        ->setFrom('Invite Bot')
                        ->setTo($email)
                        ->setSubject('Invite from WBS CMS')
                        ->setBody($template)
                        ->send();

                    if ($send)
                    {
                        $message['alert-success'] = 'Invite is successfully sent to the user!';
                    }
                    else
                    {
                        $message['alert-danger'] = 'Error of sending invite. Try later!';
                    }
                }

            }
        }

        $roles = $this->components->orm()->query(Model::ROLE)
            ->find()
            ->asArray(true);

        $this->assign('message', $message);

        $this->assign('email', $email);
        $this->assign('roleId', $roleId);

        $this->assign('inviteSystem', $inviteSystem);
        $this->assign('roles', $roles);

        $this->assign('title', 'New Item');

        return $this->render('cp:sou/invite/add');
    }

}