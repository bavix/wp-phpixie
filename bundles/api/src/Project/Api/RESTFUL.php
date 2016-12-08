<?php

namespace Project\Api;

use Project\Api\ENUM\REST;

class RESTFUL
{

    /**
     * @var string|int
     */
    protected static $status;

    /**
     * @param int $default
     *
     * @return int|string
     */
    public static function getStatus($default = REST::OK)
    {
        static::setDefaultStatus($default);

        return static::$status;
    }

    /**
     * @param $default
     */
    public static function setDefaultStatus($default)
    {
        if (!static::$status)
        {
            static::$status = $default;
        }
    }

    /**
     * @param int $value
     */
    public static function setStatus($value)
    {
        static::$status = $value;
    }

}