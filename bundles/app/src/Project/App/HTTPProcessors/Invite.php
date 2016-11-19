<?php

namespace Project\App\HTTPProcessors;

use PHPixie\HTTP\Request;

use PHPixie\Validate\Results\Result\Field;
use PHPixie\Validate\Rules\Rule\Data\Document;
use PHPixie\Validate\Results\Result\Root as RootResult;
use Project\Model;

use PHPixie\AuthHTTP\Providers\Cookie as CookieProvider;
use PHPixie\AuthHTTP\Providers\Session as SessionProvider;
use PHPixie\AuthLogin\Providers\Password as PasswordProvider;
use Project\ORM\User\User;

class Invite extends Processor
{

    public function defaultAction(Request $request)
    {

        $token = $request->query()->getRequired('token');

        if (mb_strlen($token) !== 8)
        {
            throw new \Exception();
        }

        $invite = $this->components->orm()->query(Model::INVITE)
            ->where('token', $token)
            ->where('expires', '>=', time())
            ->where('activated', '=', 0)
            ->findOne();

        if (!$invite)
        {
            throw new \Exception();
        }

        $data = $request->data();

        if ($request->method() === 'POST')
        {

            $validator  = $this->getSignupValidator();
            $formSignUp = $validator->validate($data->get());

            if ($formSignUp->isValid())
            {
                $domain = $this->components->auth()->domain();

                /**
                 * @var PasswordProvider $passwordProvider
                 */
                $passwordProvider = $domain->provider('password');

                /**
                 * @var User $user
                 */
                $user               = $this->components->orm()->createEntity(Model::USER);
                $user->login        = $data->get('login');
                $user->lastname     = $data->get('lastname');
                $user->name         = $data->get('name');
                $user->email        = $invite->email;
                $user->roleId       = $invite->roleId;
                $user->passwordHash = $passwordProvider->hash($data->get('password'));
                $user->save();

                $invite->activated = 1;
                $invite->save();

                $domain->setUser($user, 'session');

                /**
                 * @var SessionProvider $sessionProvider
                 */
                $sessionProvider = $domain->provider('session');
                $sessionProvider->persist();

                return $this->redirectResponse('cp.processor');
            }
        }

        $uri = $request->uri();

        $urlPath = $uri->getScheme() . '://' . $uri->getHost() . '/svg/no-avatar-140.png';

        $grAvatar = 'https://secure.gravatar.com/avatar/' . md5($invite->email);

        $grAvatar .= '?s=' . 100;
        $grAvatar .= '&d=' . $urlPath;

        $this->assign('avatar', $grAvatar);
        $this->assign('invite', $invite);

        $this->assign('formSignUp', $formSignUp ?? null);

        return $this->render('app:invite/default');
    }

    /**
     * Builds a validator for the signup form
     *
     * @return \PHPixie\Validate\Validator
     */
    protected function getSignupValidator()
    {
        /**
         * @var $validator \PHPixie\Validate\Validator
         */
        $validator = $this->components->validate()->validator();

        /**
         * @var $document \PHPixie\Validate\Rules\Rule\Data\Document
         */
        $document = $validator->rule()->addDocument();

        $document->valueField('login')
            ->required()
            ->addFilter()
            ->minLength(3)
            ->maxLength(16);

        $validator->rule()->callback(function (RootResult $result, $value)
        {
            if ($result->isValid())
            {
                $user = $this->components->orm()->query(Model::USER)
                    ->where('login', $value['login'])
                    ->findOne();

                if ($user !== null)
                {
                    $result->addCustomError('loginInUse');
                }
            }
        });

        $document->valueField('lastname')
            ->required()
            ->addFilter()
            ->alpha()
            ->minLength(1)
            ->maxLength(40);

        $document->valueField('name')
            ->required()
            ->addFilter()
            ->alpha()
            ->minLength(1)
            ->maxLength(40);

        $document->valueField('password')
            ->required()
            ->addFilter()
            ->minLength(6);

        return $validator;
    }


}