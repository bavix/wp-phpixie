<?php

namespace Project\Cp\HTTPProcessors;

use PHPixie\AuthLogin\Providers\Password as PasswordProvider;
use PHPixie\HTTP\Request;
use PHPixie\HTTP\Responses\Response;
use Project\App\HTTPProcessors\Processor;
use Project\ORM\User\User;

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
        $this->assign('title', 'Auth');

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
            $redirect = $request->query()->get('redirect');

            $this->assign('redirect', $redirect);

            return $this->render('cp:auth/default');
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
            $this->assign('loginFailed', true);

            return $this->render('cp:auth/default');
        }

        // Generate persistent login cookie
        $provider = $domain->provider('cookie');
        $provider->persist();

        $redirect = $data->get('redirect');

        if ($redirect)
        {
            return $this->redirect($redirect);
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
        return $this->redirectResponse('cp.processor');
    }

}