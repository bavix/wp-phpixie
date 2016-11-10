<?php

namespace Project\Framework;

use \Project\Template\Template;

class Components extends \PHPixie\BundleFramework\Components
{

    /**
     * @return Template
     */
    protected function buildTemplate()
    {
        $configuration = $this->builder->configuration();
        $extensions = $this->builder->extensions();

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