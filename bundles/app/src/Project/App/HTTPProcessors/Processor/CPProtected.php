<?php

namespace Project\App\HTTPProcessors\Processor;

use PHPixie\Framework\Processors\HTTP\Response\NotFound;
use PHPixie\HTTP\Request;
use PHPixie\Processors\Exception;
use Project\App\Builder;
use Project\App\HTTPProcessors\Processor;
use Project\App\Model;
use Project\App\ORM\Menu\Menu;
use Project\App\ORM\User\User;

/**
 * Base processor that allows only logged in users
 */
abstract class CPProtected extends Processor
{

    /**
     * @var string
     */
    protected $permission = 'cp';

    /**
     * @var string
     */
    protected $resolverPath = 'app.cp.processor';

    /**
     * @var User
     */
    protected $user;

    public function breadcrumbs($action, Menu $current)
    {
        $pool = $this->builder->cache();

        $key = __FUNCTION__ . $action . $current->id();

        if ($pool->hasItem($key) === false)
        {
            $item = $pool->getItem($key);

            if ($action !== 'default')
            {
                $breadcrumbs[] = $action;
            }

            $breadcrumbs[] = [
                'title'    => $current->title,
                'httpPath' => $current->httpPath(),
            ];

            do
            {
                $current = $current->menu();

                if ($current)
                {
                    $breadcrumbs[] = [
                        'title'    => $current->title,
                        'httpPath' => $current->httpPath(),
                    ];
                }
            }
            while ($current && $current->parentId);

            $item->set(array_reverse($breadcrumbs));

            $pool->save($item);
        }

        return $pool->getItem($key)->get();
    }

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
            $http = $this->builder->frameworkBuilder()->http();

            $url = $http->generatePath('app.cp.processor', array(
                'cpProcessor' => 'auth'
            ));

            $redirect = $request->server()->get('REQUEST_URI');

            if ($redirect)
            {
                $url .= '?redirect=' . $redirect;
            }

            return $this->redirect($url);
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

        $action = $request->attributes()->get('action');

        $this->variables['breadcrumbs'] = $this->breadcrumbs($action, $currentMenu);

        return parent::process($request);
    }

}