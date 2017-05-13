<?php

namespace Project\Extension;

use PHPixie\BundleFramework\Components;
use PHPixie\DefaultBundle\Builder;
use PHPixie\DefaultBundle\Processor\HTTP\Actions;
use PHPixie\HTTP\Request;
use PHPixie\HTTP\Responses\Response;
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
     * @var User|null
     */
    protected $user;

    /**
     * @var array
     */
    protected $variables = [
        'assets' => \Project\Assets::class
    ];

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
        if (!$this->user)
        {
            $this->user = $this->components->auth()->domain()->user();
        }

        return $this->user;
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
    public function assignPush($key, $value)
    {

        if (!is_array($this->variables[$key]))
        {
            $this->variables[$key] = [];
        }

        $this->variables[$key][] = $value;

    }

    /**
     * @param $key
     * @param $value
     */
    public function assign($key, $value)
    {
        $this->variables[$key] = $value;
    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     * @param array $defaults
     */
    public function query($query, Request $request, array $defaults = [])
    {

        // ordering
        $this->querySort($query, $request, $defaults['sort'] ?? []);

        // equal id = 1
        $this->queryTerms($query, $request, $defaults['terms'] ?? []);

        // less id < 1
        $this->queryLess($query, $request, $defaults['less'] ?? []);

        // greater id > 1
        $this->queryGreater($query, $request, $defaults['greater'] ?? []);

        // queries name LIKE '%OTIFOR%' ~ Rotiform
        $this->queryQueries($query, $request, $defaults['queries'] ?? []);
    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     * @param array $defaults
     */
    protected function querySort($query, Request $request, array $defaults)
    {

        /**
         * [ [ name, sort ], [ id, sort ] ]
         *
         * @var array $sort
         */
        $sort = $request->query()->get('sort', $defaults);

        foreach ($sort as $field => $direction)
        {
            // order by
            $query->orderBy($field, $direction);
        }

    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     * @param array $defaults
     */
    protected function queryTerms($query, Request $request, array $defaults)
    {

        /**
         * equal
         *
         * @var array $terms
         */
        $terms = $request->query()->get('terms', $defaults);

        foreach ($terms as $column => $value)
        {
            if (is_array($value))
            {
                $query->where($column, 'in', $value);
            }
            else
            {
                $query->where($column, $value);
            }
        }

    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     * @param array $defaults
     */
    protected function queryGreater($query, Request $request, array $defaults)
    {

        /**
         * greater
         *
         * @var array $terms
         */
        $terms = $request->query()->get('greater', $defaults);

        foreach ($terms as $column => $value)
        {
            $query->where($column, '>', $value);
        }

    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     * @param array $defaults
     */
    protected function queryLess($query, Request $request, array $defaults)
    {

        /**
         * less
         *
         * @var array $terms
         */
        $terms = $request->query()->get('less', $defaults);

        foreach ($terms as $column => $value)
        {
            $query->where($column, '<', $value);
        }

    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     * @param array $defaults
     */
    protected function queryQueries($query, Request $request, array $defaults)
    {

        /**
         * @var array $queries
         */
        $queries = $request->query()->get('queries', $defaults);

        foreach ($queries as $column => $value)
        {
            $query->where($column, 'like', "%$value%");
        }

    }

}