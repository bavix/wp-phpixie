<?php

namespace Project\Extension\Template;

class Template extends \PHPixie\Template
{

    protected function buildBuilder(
        $slice,
        $filesystemLocator,
        $configData,
        $filesystemRoot,
        $externalExtensions,
        $externalFormats
    )
    {
        return new Builder(
            $slice,
            $filesystemLocator,
            $configData,
            $filesystemRoot,
            $externalExtensions,
            $externalFormats
        );
    }

}