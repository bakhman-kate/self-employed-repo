<?php

namespace App\Service\Cache;

use Predis\Client;

class RedisService implements CacheInterface
{
    private Client $client;

    public function __construct()
    {
        $this->client = new Client(
            [
                'scheme' => 'tcp',
                'host'   => 'redis',
                'port'   => 6379,
            ],
            [
                'cluster' => 'redis',
                //'connections' => extension_loaded('phpiredis') ? 'phpiredis' : 'default',
            ]
        );
    }

    public function getValue($key): mixed
    {
        return json_decode($this->client->get($key), true);
    }

    public function setValue($key, $value, int $expirationTime): bool
    {
        $this->client->set($key, json_encode($value));
        $this->client->expire($key, $expirationTime);

        return true;
    }
}
