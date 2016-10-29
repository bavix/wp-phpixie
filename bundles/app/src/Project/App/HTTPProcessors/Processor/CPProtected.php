<?php

namespace Project\App\HTTPProcessors\Processor;

use PHPixie\Framework\Processors\HTTP\Response\NotFound;
use PHPixie\HTTP\Request;
use PHPixie\Processors\Exception;
use Project\App\Builder;
use Project\App\HTTPProcessors\Processor;
use Project\App\Model;
use Project\App\ORM\User\User;
use Project\Breadcrumb;

/**
 * Base processor that allows only logged in users
 */
abstract class CPProtected extends Processor
{

    /**
     * @var string
     */
    protected $resolverPath = 'app.cp.processor';

    /**
     * @var User
     */
    protected $user;

    /**
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        parent::__construct($builder);
    }

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

        if (!$this->user->hasPermission('cp'))
        {
            throw new \PHPixie\Processors\Exception("Access Denied");
        }

        $this->variables['user']      = $this->user;
        $this->variables['menuList']  = $this->components->orm()
            ->query(Model::Menu)
            ->where('parentId', 0)
            ->orderAscendingBy('sortId')
            ->find();

        $attributes = $request->attributes();

        $this->variables['currentMenu'] = $this->components->orm()
            ->query(Model::Menu)
            ->where('processor', $attributes->get('cpProcessor'))
            ->where('action', $attributes->get('action'))
            ->findOne();

        return parent::process($request);
    }

}