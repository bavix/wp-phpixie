<?php

namespace Project\Api\HTTPProcessors;

use OAuth2\GrantType\AuthorizationCode;
use OAuth2\GrantType\ClientCredentials;
use OAuth2\GrantType\RefreshToken;
use OAuth2\GrantType\UserCredentials;
use PHPixie\HTTP\Request;
use PHPixie\Paginate\Pager;
use Project\Api\ENUM\REST;
use Project\Api\Exceptions\Unauthorized;
use Project\Api\RESTFUL;
use Project\Extension\Util;
use Project\Model;

class AuthProcessor extends Processor
{

    /**
     * @var array
     */
    protected $allow = [];

    /**
     * @var \Project\OAuth2\PDO
     */
    protected $oauthPdo;

    /**
     * @return \OAuth2\Request
     */
    protected function globalsRequest()
    {
        return \OAuth2\Request::createFromGlobals();
    }


    /**
     * @return null|\Project\ORM\User\User
     * @throws \PHPixie\ORM\Exception\Query
     */
    public function loggedUser()
    {
        if (!$this->user)
        {
            if ($this->server()->verifyResourceRequest($this->globalsRequest()))
            {
                $accessToken = $this->server()->getAccessTokenData($this->globalsRequest());

                if ($accessToken['user_id'])
                {
                    $this->user = $this->components->orm()->query(Model::USER)
                        ->in($accessToken['user_id'])
                        ->findOne();

                    $this->components->auth()->domain()->setUser($this->user, 'default');
                }

            }
        }

        return parent::loggedUser();
    }

    protected function oauthPDO()
    {
        if (!$this->oauthPdo)
        {
            $this->oauthPdo = new \Project\OAuth2\PDO($this->builder);
        }

        return $this->oauthPdo;
    }

    /**
     * @return \OAuth2\Server
     */
    protected function server()
    {
        $storage = $this->oauthPDO();

        $server = new \OAuth2\Server($storage, [
            'access_lifetime' => 94672800 // 3 year
        ]);

        $server->addGrantType(new ClientCredentials($storage));
        $server->addGrantType(new AuthorizationCode($storage));
        $server->addGrantType(new RefreshToken($storage));
        $server->addGrantType(new UserCredentials($storage));

        return $server;
    }

    /**
     * @return bool|mixed|string
     */
    protected function accessDenied()
    {
        $server         = $this->server();
        $globalsRequest = $this->globalsRequest();

        if (!$this->loggedUser() && !$server->verifyResourceRequest($globalsRequest))
        {
//            /**
//             * @var $response \OAuth2\Response
//             */
//            $response = $server->getResponse();
//
//            return $response->getResponseBody();

            RESTFUL::setStatus(REST::UNAUTHORIZED);

            throw new Unauthorized();
        }

        return false;
    }

    /**
     * @param Request $httpRequest
     *
     * @return string
     */
    public function getActionNameFor($httpRequest)
    {
        $method = $httpRequest->method();
        $method = strtolower($method);
        $method = ucfirst($method);

        $action = $httpRequest->attributes()->get('action');

        if ($httpRequest->attributes()->get('nextId'))
        {
            $action .= '-item';
        }

        return Util::camelCase($action) . $method;
    }

    /**
     * @param Request $request
     *
     * @return mixed
     * @throws \PHPixie\Processors\Exception
     */
    public function process($request)
    {
        $method = $request->method();
        $method = strtolower($method);
        $method = ucfirst($method);

        $action = $request->attributes()->get('action');

        if (!in_array($action . $method, $this->allow, true))
        {
            $accessDenied = $this->accessDenied();

            if ($accessDenied)
            {
                return $accessDenied;
            }
        }

        return parent::process($request);
    }

    /**
     * @param Pager $pager
     *
     * @return array
     */
    public function pager(Pager $pager)
    {
        return [
            // current page [example: 1]
            'currentPage' => $pager->currentPage(),

            // page size [example: 50]
            'pageSize'    => $pager->pageSize(),

            // item count [example: 800]
            'itemCount'   => (int)$pager->itemCount(),

            // page count [example: 800/50]
            'pageCount'   => $pager->pageCount(),

            // return array with limit [50]
            'data'        => $pager->getCurrentItems()->asArray(true)
        ];
    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     */
    public function query($query, Request $request)
    {

        // ordering
        $this->querySort($query, $request);

        // equal id = 1
        $this->queryTerms($query, $request);

        // less id < 1
        $this->queryLess($query, $request);

        // greater id > 1
        $this->queryGreater($query, $request);

        // queries name LIKE '%OTIFOR%' ~ Rotiform
        $this->queryQueries($query, $request);
    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     */
    protected function querySort($query, Request $request)
    {

        /**
         * [ [ name, sort ], [ id, sort ] ]
         *
         * @var array $sort
         */
        $sort = $request->query()->get('sort', []);

        foreach ($sort as $field => $direction)
        {
            // order by
            $query->orderBy($field, $direction);
        }

    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     */
    protected function queryTerms($query, Request $request)
    {

        /**
         * equal
         *
         * @var array $terms
         */
        $terms = $request->query()->get('terms', []);

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
     */
    protected function queryGreater($query, Request $request)
    {

        /**
         * greater
         *
         * @var array $terms
         */
        $terms = $request->query()->get('greater', []);

        foreach ($terms as $column => $value)
        {
            $query->where($column, '>', $value);
        }

    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     */
    protected function queryLess($query, Request $request)
    {

        /**
         * less
         *
         * @var array $terms
         */
        $terms = $request->query()->get('less', []);

        foreach ($terms as $column => $value)
        {
            $query->where($column, '<', $value);
        }

    }

    /**
     * @param \PHPixie\ORM\Models\Type\Database\Implementation\Query $query
     * @param Request                                                $request
     */
    protected function queryQueries($query, Request $request)
    {

        /**
         * @var array $queries
         */
        $queries = $request->query()->get('queries', []);

        foreach ($queries as $column => $value)
        {
            $query->where($column, 'like', "%$value%");
        }

    }

}