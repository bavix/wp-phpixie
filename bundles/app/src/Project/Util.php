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
     * @param $attributes
     * @param $bundle
     *
     * @return mixed|string
     */
    public static function httpPath($attributes, $bundle = 'app')
    {
        $last = isset($attributes['action']) ? 'action' : 'processor';
        $last = isset($attributes['id']) ? 'item' : $last;

        $path = $last;
        if ($attributes['nextProcessor'])
        {
            $path =
                $attributes['processor'] . '.' .
                $attributes['cpProcessor'] . '.' . $last;
        }
        else if ($attributes['cpProcessor'])
        {
            $path = $attributes['processor'] . '.' . $last;
        }

        $path = preg_replace('~\.+~', '.', trim($path, '.'));

        return $bundle . '.' . $path;
    }

    /**
     * @param string $url
     *
     * @return array
     */
    public static function httpWithURL($url)
    {
        $processor     = null;
        $cpProcessor   = null;
        $nextProcessor = null;
        $action        = null;
        $id            = null;

        list($processors, $attributes) =
            explode('@', $url, 2) +
            [null, null];

        if ($processors)
        {
            list($processor, $cpProcessor, $nextProcessor) =
                explode('.', $processors, 3) +
                [null, null, null];
        }

        if ($attributes)
        {
            list($action, $id) =
                explode('.', $attributes, 2) +
                [null, null];
        }

        $attributes = [
            'processor'     => $processor,
            'cpProcessor'   => $cpProcessor,
            'nextProcessor' => $nextProcessor,
            'action'        => $action,
            'id'            => $id
        ];

        return [
            'url'        => static::httpPath($attributes),
            'attributes' => $attributes
        ];
    }

}