<?php

namespace Project;

class Util
{

    /**
     * @param $name string
     *
     * @return string
     */
    public static function snakeCase($name)
    {
        return strtolower(preg_replace('~([a-z])([A-Z])~', '\\1-\\2', $name));
    }

    /**
     * @param $input
     *
     * @return string
     */
    protected static final function upperCase($input)
    {
        return strtoupper(end($input));
    }

    /**
     * @param $name string
     *
     * @return string
     */
    public static function camelCase($name)
    {
        return preg_replace_callback('~-([a-z])~', 'static::upperCase', $name);
    }

    /**
     * @param $array
     *
     * @return array
     */
    public static function unique(array $array)
    {
        return array_keys(array_flip($array));
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public static function httpPath($url)
    {
        $action = null;
        $id     = null;

        $null = [null, null, null];

        list($processors, $attributes) = explode('@', $url) + $null;

        if ($processors)
        {
            list($processor, $cpProcessor, $nextProcessor) = explode('.', $processors) + $null;
        }

        if ($attributes)
        {
            list($action, $id) = explode('.', $attributes) + $null;
        }

        return compact(
            'processor',
            'cpProcessor',
            'nextProcessor',
            'action',
            'id'
        );
    }

}