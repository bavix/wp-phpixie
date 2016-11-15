<?php

namespace Project\App\HTTPProcessors;

use PHPixie\HTTP\Request;

use PHPixie\Validate\Results\Result\Field;
use PHPixie\Validate\Rules\Rule\Data\Document;
use PHPixie\Validate\Results\Result\Root as RootResult;
use Project\App\Model;

use PHPixie\AuthHTTP\Providers\Cookie as CookieProvider;
use PHPixie\AuthHTTP\Providers\Session as SessionProvider;
use PHPixie\AuthLogin\Providers\Password as PasswordProvider;
use Project\App\ORM\User\User;

class Invite extends Processor
{

    public function defaultAction(Request $request)
    {

        $token = $request->query()->getRequired('token');

        $user = $this->loggedUser();

        if ($user || mb_strlen($token) !== 8)
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

        if ($request->method() === 'POST')
        {

            $data = $request->data();

            $validator = $this->getSignupValidator();
            $result    = $validator->validate($data->get());

            if ($result->isValid())
            {
                var_dump('valid');
                die;

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
                $user->email        = $invite->email;
                $user->passwordHash = $passwordProvider->hash($data->get('password'));
                $user->save();

                $domain->setUser($user, 'session');

                /**
                 * @var SessionProvider $sessionProvider
                 */
                $sessionProvider = $domain->provider('session');
                $sessionProvider->persist();

            }

        }

        $uri = $request->uri();

        $urlPath = $uri->getScheme() . '://' . $uri->getHost() . '/svg/no-avatar-140.png';

        $grAvatar = 'https://secure.gravatar.com/avatar/' . md5($invite->email);

        $grAvatar .= '?s=' . 100;
        $grAvatar .= '&d=' . $urlPath;

        $this->variables['avatar'] = $grAvatar;
        $this->variables['invite'] = $invite;

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

        $document->valueField('password')
            ->required()
            ->addFilter()
            ->minLength(8);

        return $validator;
    }


}