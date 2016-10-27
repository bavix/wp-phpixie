<?php

namespace Project\App\HTTPProcessors\Processor;

use PHPixie\Framework\Processors\HTTP\Response\NotFound;
use PHPixie\HTTP\Request;
use PHPixie\Processors\Exception;
use Project\App\HTTPProcessors\Processor;
use Project\App\ORM\User\User;

/**
 * Base processor that allows only logged in users
 */
abstract class CPProtected extends Processor
{

    /**
     * @var User
     */
    protected $user;

    /**
     * Only process the request if the user is logged in.
     * Otherwise redirect to the login page.
     *
     * @param Request $request
     *
     * @return \PHPixie\HTTP\Responses\Response
     * @throws \PHPixie\Processors\Exception
     */
    public function process($request)
    {
        $this->user = $this->loggedUser();

        if (!$this->user)
        {
            return $this->redirectResponse('app.cp.processor', array(
                'cpProcessor' => 'auth'
            ));
        }

        if (!$this->user->hasPermission('cp.auth'))
        {
            throw new \PHPixie\Processors\Exception("No action method found for value");
        }

        return parent::process($request);
    }

}