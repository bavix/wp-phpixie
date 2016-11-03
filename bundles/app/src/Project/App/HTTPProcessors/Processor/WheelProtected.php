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
abstract class WheelProtected extends CPProtected
{

    /**
     * @var string
     */
    protected $resolverPath = 'app.cp.wheel.processor';

    /**
     * Only process the request if the user is logged in.
     * Otherwise redirect to the login page.
     *
     * @param Request $request
     *
     * @return \PHPixie\HTTP\Responses\Response
     *
     * @throws Exception
     * @throws \PHPixie\ORM\Exception\Query
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

        if (!$this->user->hasPermission('cp.wheel'))
        {
            $this->accessDenied();
        }

        $attributes = $request->attributes();

        $processor = $attributes->get('wheelProcessor');
        $action    = $attributes->get('action');

        $permission = 'cp.' . $processor;

        if (!$this->user->hasPermission($permission))
        {
            $this->accessDenied();
        }

        $this->variables['user']     = $this->user;
        $this->variables['menuList'] = $this->components->orm()
            ->query(Model::MENU)
            ->where('parentId', 0)
            ->orderAscendingBy('sortId')
            ->find();

        $currentMenu = $this->components->orm()
            ->query(Model::MENU)
            ->where('processor', $processor)
            ->where('action', $action)
            ->findOne();

        if (!$currentMenu)
        {
            $currentMenu = $this->components->orm()
                ->query(Model::MENU)
                ->where('processor', $processor)
                ->findOne();
        }

        $this->variables['currentMenu'] = $currentMenu;

        return parent::process($request);
    }

}