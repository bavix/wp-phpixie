<?php

namespace Project\Framework;

use Project\Extension\ORM\ORM;
use Project\Extension\Template\Template;

class Components extends \PHPixie\BundleFramework\Components
{

    /**
     * @return ORM
     */
    protected function buildOrm()
    {
        $configuration = $this->builder->configuration();

        return new ORM(
            $this->database(),
            $configuration->ormConfig(),
            $configuration->ormWrappers(),
            $this
        );
    }

    /**
     * @return Template
     */
    protected function buildTemplate()
    {
        $configuration = $this->builder->configuration();
        $extensions    = $this->builder->extensions();

        return new Template(
            $this->slice(),
            $configuration->templateLocator(),
            $configuration->templateConfig(),
            $configuration->filesystemRoot(),
            $extensions->templateExtensions(),
            $extensions->templateFormats()
        );
    }

}