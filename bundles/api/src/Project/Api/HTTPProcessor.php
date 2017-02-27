<?php

namespace Project\Api;

use PHPixie\DefaultBundle\Processor\HTTP\Builder as HttpBuilder;
use PHPixie\HTTP\Request;
use Project\Api\ENUM\REST;
use Project\Extension\Util;

class HTTPProcessor extends HttpBuilder
{

    /**
     * @var Builder
     */
    protected $builder;

    /**
     * Constructor
     *
     * @param Builder $builder
     */
    public function __construct($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return HTTPProcessors\Auth
     */
    public function buildAuthProcessor()
    {
        return new HTTPProcessors\Auth($this->builder);
    }

    /**
     * @return HTTPProcessors\Upload
     */
    public function buildUploadProcessor()
    {
        return new HTTPProcessors\Upload($this->builder);
    }

    /**
     * @return HTTPProcessors\Video
     */
    public function buildVideoProcessor()
    {
        return new HTTPProcessors\Video($this->builder);
    }

    /**
     * @return HTTPProcessors\Account
     */
    public function buildAccountProcessor()
    {
        return new HTTPProcessors\Account($this->builder);
    }

    /**
     * @param Request $httpRequest
     *
     * @return string
     */
    protected function getProcessorNameFor($httpRequest)
    {
        $processorName = $httpRequest->attributes()->get($this->attributeName);
        $processorName = Util::camelCase($processorName);

        return $processorName;
    }

    /**
     * Build 'admin' processor group
     *
     * @return HTTPProcessors\SOWProcessorBuilder
     */
    protected function buildSowProcessor()
    {
        return new HTTPProcessors\SOWProcessorBuilder($this->builder);
    }

    /**
     * Build 'admin' processor group
     *
     * @return HTTPProcessors\SOCProcessorBuilder
     */
    protected function buildSocProcessor()
    {
        return new HTTPProcessors\SOCProcessorBuilder($this->builder);
    }

    /**
     * Build 'admin' processor group
     *
     * @return HTTPProcessors\SOUProcessorBuilder
     */
    protected function buildSouProcessor()
    {
        return new HTTPProcessors\SOUProcessorBuilder($this->builder);
    }

    /**
     * @param Request $request
     *
     * @return \PHPixie\HTTP\Responses\Response
     */
    public function process($request)
    {

        try
        {
            $process = parent::process($request);

            RESTFUL::setDefaultStatus(REST::OK);
        }
        catch (\Throwable $throwable)
        {
            $process = [
                'error'             => RESTFUL::getError(),
                'error_description' => $throwable->getMessage(),
            ];
        }
        finally
        {

            if (is_array($process) || is_object($process))
            {
                $process = json_encode($process, JSON_UNESCAPED_UNICODE);
            }

            $http = $this->builder->components()->http();
            $body = $http->messages()->stringStream($process);

            return $http->responses()->response(
                $body,
                ['Content-Type' => 'application/json'],
                RESTFUL::getStatus(REST::BAD_REQUEST)
            );

        }

    }

    public function isProcessable($value)
    {
        return true; // for api
    }

}