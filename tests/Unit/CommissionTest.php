<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class CommissionTest extends TestCase
{
    public function testCommissionCalculation()
    {
        $percentage = 10;
        $amount = 1000;
        $expectedResult = "100.00";

        $result = commission($percentage, $amount);

        $this->assertSame($expectedResult, $result);
    }
}
