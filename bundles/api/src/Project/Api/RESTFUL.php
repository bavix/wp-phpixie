<?php

namespace Project\Api;

use Project\Api\ENUM\REST as RESTFULConst;

class RESTFUL
{

    /**
     * @var string|int
     */
    protected static $status = RESTFULConst::OK;

    /**
     * @return int|string
     */
    public static function getStatus()
    {
        return static::$status;
    }

    /**
     * @param int $value
     */
    public static function setStatus($value)
    {
        static::$status = $value;
    }

}