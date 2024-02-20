<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;

class ExchangeTest extends TestCase
{
    public function testCurrencyExchange()
    {
        $currency = 'JPY';
        $amount = 50000;
        $expectedResult = $amount / 161.8800;

        $result = exchange($currency, $amount);

        $this->assertEquals($expectedResult, $result);

        $currency = 'USD';
        $amount = 100;
        $expectedResult = $amount / 1.08;

        $result = exchange($currency, $amount);

        $this->assertEquals($expectedResult, $result);
    }

}
