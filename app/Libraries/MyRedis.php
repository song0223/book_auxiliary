<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Redis;

class MyRedis extends Redis
{

    public static function get($key)
    {
        $value = Redis::get($key);
        $value_serl = @unserialize($value);
        if (is_object($value_serl) || is_array($value_serl)) {
            return $value_serl;
        }
        return $value;
    }

    public static function set($key, $value)
    {
        if (is_object($value) || is_array($value)) {
            $value = serialize($value);
        }

        return Redis::set($key, $value);
    }
}