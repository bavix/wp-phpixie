<?php

namespace Project\App;

use PHPixie\DefaultBundle\Processor\HTTP\Builder as HttpBuilder;

/**
 * Handles processing of the HTTP requests
 */
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
     * Build 'invite' processor
     *
     * @return HTTPProcessors\Landing
     */
    protected function buildLandingProcessor()
    {
        return new HTTPProcessors\Landing($this->builder);
    }

    /**
     * Build 'invite' processor
     *
     * @return HTTPProcessors\Invite
     */
    protected function buildInviteProcessor()
    {
        return new HTTPProcessors\Invite($this->builder);
    }

    /**
     * Build 'webhook' processor
     *
     * @return HTTPProcessors\Webhook
     */
    protected function buildWebhookProcessor()
    {
        return new HTTPProcessors\Webhook($this->builder);
    }

    /**
     * Build 'admin' processor group
     *
     * @return HTTPProcessors\CPProcessorBuilder
     */
    protected function buildCpProcessor()
    {
        return new HTTPProcessors\CPProcessorBuilder($this->builder);
    }

}