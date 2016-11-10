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

        if ($user || mb_strlen($token) != 64)
        {
            throw new \Exception();
        }

        $data = $request->data();

        $validator = $this->getSignupValidator();
        $result    = $validator->validate($data->get());

        if (!$result->isValid())
        {
            $this->variables['signupResult'] = $result;
            $this->variables['activeTab']    = 'signUp';

            return $this->render('app:invite/default');
        }

        $domain = $this->components->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        /**
         * @var User $user
         */
        $user               = $this->components->orm()->createEntity(Model::USER);
        $user->email        = $data->get('email'); // default
        $user->passwordHash = $passwordProvider->hash($data->get('password'));
        $user->save();

        $domain->setUser($user, 'session');

        /**
         * @var SessionProvider $sessionProvider
         */
        $sessionProvider = $domain->provider('session');
        $sessionProvider->persist();

        return $this->redirectResponse('app.cp');
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
         * @var $rule \PHPixie\Validate\Rules\Rule
         */
        $rule = $validator->rule();

        /**
         * @var Document $document
         */
        $document = $rule->addDocument();
        $document->allowExtraFields();

        $document->valueField('email')
            ->required()
            ->filter('email')
            ->callback(function (Field $result, $value)
            {

                if ($result->isValid())
                {
                    $user = $this->components->orm()->query(Model::USER)
                        ->where('email', $value)
                        ->findOne();

                    if ($user !== null)
                    {
                        $result->addCustomError('emailInUse');
                    }
                }

            });

        $document->valueField('password')
            ->required()
            ->addFilter()
            ->minLength(8);

        $validator->rule()->callback(function (RootResult $result, $data)
        {
            if ($result->field('password')->isValid() && $data['passwordConfirm'] !== $data['password'])
            {
                $result->field('passwordConfirm')->addCustomError('passwordConfirm');
            }
        });

        return $validator;
    }


}