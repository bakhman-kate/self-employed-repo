<?php

namespace App\Service\TaxPayer;

interface TaxPayerInterface
{
    /**
     * Get TaxPayer status (https://npd.nalog.ru/html/sites/www.npd.nalog.ru/api_statusnpd_nalog_ru.pdf)
     *
     * @param string $inn
     * @param string $requestDate
     *
     * @return array
     */
    public function getStatus(string $inn, string $requestDate): array;
}
