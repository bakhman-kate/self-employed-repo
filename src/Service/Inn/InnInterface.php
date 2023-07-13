<?php

namespace App\Service\Inn;

interface InnInterface
{
    public const INDIVIDUAL_INN_LENGTH = 12;
    public const COMPANY_INN_LENGTH = 10;

    /**
     * Check individual INN (https://www.egrul.ru/test_inn.html)
     *
     * @param string $inn
     *
     * @return bool
     */
    public function isIndividualInn(string $inn): bool;

    /**
     * Check company INN (https://www.egrul.ru/test_inn.html)
     *
     * @param string $inn
     *
     * @return bool
     */
    public function isCompanyInn(string $inn): bool;
}
