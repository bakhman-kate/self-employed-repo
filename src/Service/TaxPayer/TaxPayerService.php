<?php

namespace App\Service\TaxPayer;

use App\Repository\TaxPayerRepository;
use App\Service\Cache\MemcachedService;
use Exception;

class TaxPayerService implements TaxPayerInterface
{
    private const STATUS_LAST_QUERY_KEY = 'status_last_query';
    private const STATUS_EXPIRE_SECONDS = 30; // Запрет на кол-во запросов с одного ip адреса (не чаще 2 раз в минуту)

    private ApiClient $apiClient;
    private MemcachedService $cacheService;
    private TaxPayerRepository $repository;

    public function __construct(
        ApiClient $apiClient,
        MemcachedService $cacheService,
        TaxPayerRepository $repository
    ) {
        $this->apiClient = $apiClient;
        $this->cacheService = $cacheService;
        $this->repository = $repository;
    }

    /**
     * @inheritDoc
     */
    public function getStatus(string $inn, string $requestDate): array
    {
        $result = ['status' => false];

        try {
            if ($status = $this->repository->getInnStatus($inn)) {
                $result = $status;
            } else {
                if ($this->cacheService->getValue(self::STATUS_LAST_QUERY_KEY)) {
                    $result['message'] = 'The requests number to the service from one IP address per unit of time has been exceeded, please try again later.';
                } else {
                    $this->cacheService->setValue(self::STATUS_LAST_QUERY_KEY, time(), self::STATUS_EXPIRE_SECONDS);

                    $result = $this->apiClient->getStatus($inn, $requestDate);
                    $this->repository->saveInnStatus($inn, $result);
                }
            }
        } catch (Exception $e) {
            $result['message'] = $e->getMessage();
            $result['code'] = $e->getCode();
        }

        return $result;
    }
}
