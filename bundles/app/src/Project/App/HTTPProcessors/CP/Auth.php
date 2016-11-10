<?php

namespace Project\App\HTTPProcessors\CP;

use PHPixie\HTTP\Request;
use PHPixie\HTTP\Responses\Response;
use Project\App\HTTPProcessors\Processor;
use PHPixie\AuthLogin\Providers\Password as PasswordProvider;
use Project\App\ORM\User\User;
use PHPixie\Template;

/**
 * Admin authorization processor
 */
class Auth extends Processor
{

    /**
     * Login page
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function defaultAction(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $this->loggedUser();

        if ($user !== null)
        {
            return $this->loggedInRedirect();
        }

        if ($request->method() === 'GET')
        {
            return $this->render('app:cp/auth/default');
        }

        return $this->handleLogin($request);
    }

    /**
     * Logout action
     *
     * @param Request $request
     *
     * @return mixed
     */
    public function logoutAction(Request $request)
    {
        $this->components->auth()->domain()->forgetUser();

        return $this->redirectResponse('app.processor');
    }

    /**
     * Handles login form processing
     *
     * @param Request $request
     *
     * @return mixed
     */
    protected function handleLogin(Request $request)
    {
        $domain = $this->components->auth()->domain();

        /**
         * @var PasswordProvider $passwordProvider
         */
        $passwordProvider = $domain->provider('password');

        $data = $request->data();

        $user = $passwordProvider->login(
            $data->getRequired('login'),
            $data->getRequired('password')
        );

        if ($user === null)
        {
            $this->variables['loginFailed'] = true;

            return $this->render('app:cp/auth/default');
        }

        return $this->loggedInRedirect();
    }

    /**
     * Redirect response used after login
     *
     * @return Response
     */
    protected function loggedInRedirect()
    {
        return $this->redirectResponse('app.cp.processor');
    }

}