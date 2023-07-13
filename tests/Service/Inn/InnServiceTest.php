<?php

namespace App\Tests\Service\Inn;

use App\Service\Inn\InnService;
use PHPUnit\Framework\TestCase;

class InnServiceTest extends TestCase
{
    public function testSuccessIndividualInn(): void
    {
        $innValidation = new InnService();
        $result = $innValidation->isIndividualInn('665805954074');

        $this->assertEquals(true, $result);
    }

    public function testFailIndividualInn(): void
    {
        $innValidation = new InnService();
        $result = $innValidation->isIndividualInn('7736207543');

        $this->assertEquals(false, $result);
    }

    public function testSuccessCompanyInn(): void
    {
        $innValidation = new InnService();
        $result = $innValidation->isCompanyInn('7736207543');

        $this->assertEquals(true, $result);
    }

    public function testFailCompanyInn(): void
    {
        $innValidation = new InnService();
        $result = $innValidation->isCompanyInn('665805954074');

        $this->assertEquals(false, $result);
    }
}
