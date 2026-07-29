<?php

namespace App\Services\Cache;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class CacheService
{
    protected $cachePrefix = 'food_ordering:';
    protected $defaultTtl = 3600; // 1 hour

    public function remember(string $key, callable $callback, int $ttl = null)
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $fullKey = $this->cachePrefix . $key;
        
        return Cache::remember($fullKey, $ttl, $callback);
    }

    public function rememberForever(string $key, callable $callback)
    {
        $fullKey = $this->cachePrefix . $key;
        return Cache::rememberForever($fullKey, $callback);
    }

    public function forget(string $key): void
    {
        $fullKey = $this->cachePrefix . $key;
        Cache::forget($fullKey);
    }

    public function forgetPattern(string $pattern): void
    {
        $keys = Redis::keys($this->cachePrefix . $pattern);
        foreach ($keys as $key) {
            Redis::del($key);
        }
    }

    public function clearAll(): void
    {
        $keys = Redis::keys($this->cachePrefix . '*');
        foreach ($keys as $key) {
            Redis::del($key);
        }
    }

    public function get(string $key, $default = null)
    {
        $fullKey = $this->cachePrefix . $key;
        return Cache::get($fullKey, $default);
    }

    public function put(string $key, $value, int $ttl = null): void
    {
        $ttl = $ttl ?? $this->defaultTtl;
        $fullKey = $this->cachePrefix . $key;
        Cache::put($fullKey, $value, $ttl);
    }

    public function has(string $key): bool
    {
        $fullKey = $this->cachePrefix . $key;
        return Cache::has($fullKey);
    }
}
