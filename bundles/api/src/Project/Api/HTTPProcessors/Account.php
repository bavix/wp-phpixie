<?php

namespace Project\Api\HTTPProcessors;

use PHPixie\HTTP\Request;

class Account extends AuthProcessor
{

    /**
     * @var array
     */
    protected $access = [
        'defaultGet'
    ];

    /**
     * @api           {get} /account User Account
     * @apiName       Get Current User Info
     * @apiGroup      Account
     *
     * @apiPermission client user
     *
     * @apiHeader     Authorization Authorization Bearer {access_token}
     *
     * @apiVersion    0.0.6
     *
     * @param Request $request
     *
     * @return null|\Project\ORM\User\User
     */
    public function defaultGetAction(Request $request)
    {
        return $this->loggedUser();
    }

}