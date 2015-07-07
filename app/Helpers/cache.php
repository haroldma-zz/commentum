<?php

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

function generateCacheKey($model, $action)
{
    return 'm_'. $model . '_ac_' . $action;
}

function generateAuthCacheKey($model, $action)
{
    return generateCacheKey($model, $action) . '_au_' . Auth::id();
}

function generateCacheKeyWithId($model, $action, $id)
{
    return generateCacheKey($model, $action) . '_id_'. $id;
}

function generateAuthCacheKeyWithId($model, $action, $id)
{
    return generateAuthCacheKey($model, $action) . '_id_' . $id;
}

function invalidateCache($key)
{
    Cache::forget($key);
}

function hasCache($key, &$cache)
{
    $cache = getCache($key);
    return $cache !== null;
}

function getCache($key)
{
    return Cache::get($key);
}

function setCache($key, $value, $expire)
{
    Cache::put($key, $value, $expire);
    return $value;
}

function setCacheWithSeconds($key, $value, $expire)
{
    Redis::setex("thesocialnetwork:" . $key, $expire, serialize($value));
    return $value;
}

function setCacheCount($key, $value)
{
    Redis::setex("thesocialnetwork:" . $key, calculateExpiry($value), $value);
    return $value;
}

function calculateExpiry($count)
{
    if ($count < 50)
        return 10;
    if ($count < 100)
        return 30;
    if ($count < 500)
        return 60;
    return 60 * 5;
}