<?php

namespace Project\App\HTTPProcessors;

use PHPixie\DefaultBundle\Processor\HTTP\Actions;
use PHPixie\BundleFramework\Components;
use PHPixie\HTTP\Responses\Response;
use Project\App\ORM\User\User;
use Project\App\Builder;

/**
 * Base processor
 */
abstract class Processor extends Actions
{

    /**
     * @var string
     */
    protected $resolverPath = 'app.processor';

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * @var Components
     */
    protected $components;

    /**
     * @var \PHPixie\Template
     */
    protected $template;

    /**
     * @var array
     */
    protected $variables = [];

    /**
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder    = $builder;
        $this->components = $builder->components();
        $this->template   = $this->components->template();
    }

    /**
     * @return User|null
     */
    protected function loggedUser()
    {
        return $this->components->auth()->domain()->user();
    }

    /**
     * @param       $resolverPath
     * @param array $attributes
     *
     * @return Response
     */
    protected function redirectResponse($resolverPath, $attributes = array())
    {
        return $this->builder->frameworkBuilder()->http()->redirectResponse(
            $resolverPath,
            $attributes
        );
    }

    public function render($path)
    {
        return $this->template->render($path, $this->variables);
    }

    /**
     * @param $url
     *
     * @return Response
     */
    protected function redirect($url)
    {
        $http = $this->builder->components()->http();

        return $http->responses()->redirect($url);
    }

    /**
     * @param       $route
     * @param array $params
     *
     * @return Response
     */
    protected function routeRedirect($route, $params = array())
    {
        $url = $this->builder->frameworkBuilder()->http()->generatePath($route, $params);

        return $this->redirect($url);
    }

}