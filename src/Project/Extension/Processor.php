<?php

namespace Project\Extension;

use PHPixie\BundleFramework\Components;
use PHPixie\DefaultBundle\Processor\HTTP\Actions;
use PHPixie\HTTP\Responses\Response;
use PHPixie\DefaultBundle\Builder;
use Project\ORM\User\User;

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
     * @param $resolverPath string
     *
     * @return Response
     */
    public function redirectWithUtil($resolverPath)
    {
        $data = Util::httpWithURL($resolverPath);

        return $this->redirectResponse($data['url'], $data['attributes']);
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
     * @param $httpRequest
     *
     * @return mixed
     */
    protected function getActionNameFor($httpRequest)
    {
        $action = $httpRequest->attributes()->get('action');

        return Util::camelCase($action);
    }

    public function render($path)
    {
        return $this->template->render($path, $this->variables);
    }

    /**
     * @param $key
     * @param $value
     */
    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
    }

}