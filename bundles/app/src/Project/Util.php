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
        $array = array_keys(array_flip($array));

        return $array;
    }

}