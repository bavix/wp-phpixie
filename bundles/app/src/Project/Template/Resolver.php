<?php

namespace Project\Template;

class Resolver extends \PHPixie\Template\Resolver
{

    public function resolve($name)
    {
        if (array_key_exists($name, $this->map))
        {
            return $this->map[$name];
        }

        if (array_key_exists($name, $this->overrides))
        {
            $templateName = $this->overrides[$name];

        }
        else
        {
            $templateName = $name;
        }

        $file = $this->filesystemLocator->locate($templateName);
        if ($file === null)
        {
            throw new \PHPixie\Template\Exception("Template '$name' could not be found");
        }

        $file = $this->compiler->compile($file, $templateName);

        $this->map[$name] = $file;

        return $file;
    }

}