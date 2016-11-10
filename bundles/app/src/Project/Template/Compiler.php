<?php

namespace Project\Template;

class Compiler extends \PHPixie\Template\Compiler
{

    public function compile($path, $templateName = null)
    {
        $format = $this->formats->getByFilename($path);

        if ($format === null)
        {
            return $path;
        }

        if ($templateName)
        {
            $templateName = str_replace(':', '/', $templateName);
            $cachePath    = $this->cacheDirectory() . $templateName . '.php';
        }
        else
        {
            $hash      = crc32($path);
            $cachePath = $this->cacheDirectory() . '/' . $hash . '.php';
        }

        $dirPath = dirname($cachePath);

        if (!is_dir($dirPath))
        {
            mkdir($dirPath, 0777, true);
        }

        if (!file_exists($cachePath) || filemtime($cachePath) < filemtime($path))
        {
            $compiled = $format->compile($path);
            file_put_contents($cachePath, $compiled);
        }

        return $cachePath;
    }

}