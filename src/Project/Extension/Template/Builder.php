<?php

namespace Project\Extension\Template;

class Builder extends \PHPixie\Template\Builder
{

    protected function buildCompiler()
    {
        return new Compiler(
            $this->filesystemRoot,
            $this->formats(),
            $this->configData->slice('compiler')
        );
    }

    protected function buildResolver()
    {
        return new Resolver(
            $this->compiler(),
            $this->filesystemLocator,
            $this->configData->slice('resolver')
        );
    }

}