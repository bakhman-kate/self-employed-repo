<?php

namespace App\Repository;

use App\Service\Cache\RedisService;

class TaxPayerRepository
{
    private const STATUS_KEY = '_status';
    private const STATUS_EXPIRE_SECONDS = 24*60*60;

    private RedisService $cacheService;

    public function __construct(RedisService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    public function getInnStatus(string $inn): ?array
    {
        $value = $this->cacheService->getValue($inn . self::STATUS_KEY);
        return $value !== false ? $value : null;
    }

    public function saveInnStatus(string $inn, array $status): bool
    {
        return $this->cacheService->setValue(
            $inn . self::STATUS_KEY,
            $status,
            self::STATUS_EXPIRE_SECONDS
        );
    }
}
