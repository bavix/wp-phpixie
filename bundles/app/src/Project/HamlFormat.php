<?php

namespace Project;

use MtHaml\Environment;
use PHPixie\Template\Formats\Format;

class HamlFormat implements Format
{

    /**
     * @var Environment
     */
    protected $mtHaml;

    /**
     * HamlFormat constructor.
     */
    public function __construct()
    {
        $this->mtHaml = new Environment('php');
    }

    /**
     * @return array
     */
    public function handledExtensions()
    {
        return array('haml');
    }

    /**
     * @param string $file
     *
     * @return mixed
     */
    public function compile($file)
    {
        $contents = file_get_contents($file);

        $contents = preg_replace_callback('#^([ \t]*)partial\:(.*?)[ \t]*$#m', function ($match)
        {
            $partial = trim($match[2]);

            return "{$match[1]}- include(\$this->resolve({$partial}));";
        }, $contents);

        return $this->mtHaml->compileString($contents, $file);
    }

}