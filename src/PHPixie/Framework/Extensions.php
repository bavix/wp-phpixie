<?php

namespace PHPixie\Framework;

class Extensions
{
    protected $builder;
    
    public function __construct($builder)
    {
        $this->builder = $builder;
    }
    
    public function templateExtensions()
    {
        return array(
            new Extensions\Template\Extension\RouteTranslator(
                'http',
                $this->builder->http()->routeTranslator()
            )
        );
    }
    
    public function templateFormats()
    {
        return array();
    }
}