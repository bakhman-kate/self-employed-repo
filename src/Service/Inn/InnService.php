<?php

namespace App\Service\Inn;

class InnService implements InnInterface
{
    /**
     * @inheritDoc
     */
    public function isIndividualInn(string $inn): bool
    {
        $innLength = strlen($inn);

        if ($innLength === self::INDIVIDUAL_INN_LENGTH) {
            $firstControlNumber = $this->getControlNumber($inn, [7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
            $secondControlNumber = $this->getControlNumber($inn, [3, 7, 2, 4, 10, 3, 5, 9, 4, 6, 8]);
            $isValidByFirstControlNumber = $firstControlNumber === (int) $inn[$innLength-2];
            $isValidBySecondControlNumber = $secondControlNumber === (int) $inn[$innLength-1];

            return $isValidByFirstControlNumber && $isValidBySecondControlNumber;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function isCompanyInn(string $inn): bool
    {
        $innLength = strlen($inn);

        if ($innLength === self::COMPANY_INN_LENGTH) {
            $controlNumber = $this->getControlNumber($inn, [2, 4, 10, 3, 5, 9, 4, 6, 8]);
            return $controlNumber === (int) $inn[$innLength-1];
        }

        return false;
    }

    private function getControlNumber(string $str, array $multipliers): int
    {
        $sum = 0;
        $strLength = strlen($str);

        foreach ($multipliers as $i => $multiplier) {
            if ($i < $strLength) {
                $sum += $multiplier * (int) $str[$i];
            }
        }

        $controlNumber = $sum % 11;
        if ($controlNumber > 9) {
            $controlNumber %= 10;
        }

        return $controlNumber;
    }
}
