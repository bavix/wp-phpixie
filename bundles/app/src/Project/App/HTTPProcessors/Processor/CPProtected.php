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
use Project\Util;

/**
 * Base processor that allows only logged in users
 */
abstract class CPProtected extends Processor
{

    /**
     * @var string
     */
    protected $permission     = 'cp';

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

    protected function accessDenied()
    {
        throw new \PHPixie\Processors\Exception("Access Denied");
    }

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

        $attributes = $request->attributes();

        $processor     = $attributes->get('processor');
        $cpProcessor   = $attributes->get('cpProcessor');
        $nextProcessor = $attributes->get('nextProcessor');

        $permission = implode('.', [
            $processor,
            $cpProcessor,
            $nextProcessor,
        ]);

        $permission = preg_replace('~\.+~', '.', $permission);
        $permission = trim($permission, '.');

        if (!$this->user->hasPermission($permission))
        {
            $this->accessDenied();
        }

        $orm = $this->components->orm();

        $httpPath = $permission;

        $currentMenu = $orm
            ->query(Model::MENU)
            ->where('httpPath', $httpPath)
            ->findOne();

        $this->variables['user']        = $this->user;
        $this->variables['currentMenu'] = $currentMenu;
        $this->variables['menuList']    = $orm->query(Model::MENU)
            ->where('parentId', 0)
            ->orderAscendingBy('sortId')
            ->find();

        return parent::process($request);
    }

}