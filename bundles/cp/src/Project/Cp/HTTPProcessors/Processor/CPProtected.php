<?php

namespace Project\Cp\HTTPProcessors\Processor;

use PHPixie\HTTP\Request;
use PHPixie\Processors\Exception;
use Project\Cp\Builder;
use Project\App\HTTPProcessors\Processor;
use Project\Extension\Util;
use Project\Model;
use Project\ORM\Menu\Menu;
use Project\ORM\User\User;

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
    protected $resolverPath = 'cp.processor';

    /**
     * @var User
     */
    protected $user;

    /**
     * @param string $url
     * @param string $text
     * @param string $icon
     * @param string $class
     */
    public function addItemButton($url, $text = null, $icon = null, $class = null)
    {
        $data = Util::httpWithURL($url);

        $url        = $data['url'];
        $attributes = $data['attributes'];

        switch ($attributes['action'])
        {
            case 'add':
                $text  = 'Add Item';
                $icon  = 'fa-plus';
                $class = 'btn-success';
                break;

            case 'pull-request':
                $text  = 'Pull Request';
                $icon  = 'fa-chain-broken';
                $class = 'btn-warning';
                break;

            case 'delete':
                $text  = 'Delete Item';
                $icon  = 'fa-trash';
                $class = 'btn-danger trash-data';
                break;
            default:
                break;
        }

        $http = $this->builder->frameworkBuilder()->http();

        $url = $http->generatePath($url, $attributes);

        $this->variables['actions'][] = [
            'url'   => $url,
            'class' => $class,
            'icon'  => $icon,
            'text'  => $text
        ];
    }

    public function breadcrumbs($action, Menu $current)
    {
        $pool = $this->builder->frameworkBuilder()->cache();

        $key = __FUNCTION__ . $action . $current->id();

        if ($pool->hasItem($key) === false)
        {
            $item = $pool->getItem($key);

            if ($action !== 'default')
            {
                $breadcrumbs[] = ucfirst($action);
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

        $this->variables['actions'] = [];
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

            $url = $http->generatePath('cp.processor', array(
                'processor' => 'auth'
            ));

            $redirect = $request->server()->get('REQUEST_URI');

            if ($redirect)
            {
                $url .= '?redirect=' . $redirect;
            }

            return $this->redirect($url);
        }

        $this->variables['breadcrumbs'] = [];

        $attributes = $request->attributes();

        $processor     = $attributes->get('processor');
        $nextProcessor = $attributes->get('nextProcessor');

        $permission = implode('.', [
            $this->builder->bundleName(),
            $processor,
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