<?php

namespace App\Service\Cache;

interface CacheInterface
{
    public function getValue($key): mixed;

    public function setValue($key, $value, int $expirationTime): bool;
}
