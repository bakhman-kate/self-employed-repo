<?php

namespace App\Service\Cache;

use Memcached;

class MemcachedService implements CacheInterface
{
    private Memcached $storage;

    public function __construct(string $host, int $port)
    {
        $this->storage = new Memcached();
        $this->storage->addServer($host, $port);
    }

    public function getValue($key): mixed
    {
        return $this->storage->get($key);
    }

    public function setValue($key, $value, int $expirationTime): bool
    {
        return $this->storage->set($key, $value, time() + $expirationTime);
    }
}
