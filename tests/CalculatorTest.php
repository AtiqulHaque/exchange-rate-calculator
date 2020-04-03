<?php
use App\Calculator;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function testFileReadWhenEmpty(): void
    {
        $this->calculatorService = $this->getMockBuilder(Calculator::class)
            ->setMethods(['readFile'])
            ->getMock();
        $this->calculatorService->method('readFile')->willReturn([]);
        $this->calculatorService->calculateCommission('demo-file-path');
        $this->assertEquals(0,count($this->calculatorService->getCommissions()));
    }

    public function testCalculationWhenLookupIsEmpty(): void
    {
        $this->calculatorService = $this->getMockBuilder(Calculator::class)
            ->setMethods(['readFile','getLookupValue'])
            ->getMock();
        $this->calculatorService->method('readFile')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $this->calculatorService->method('getLookupValue')->willReturn(false);
        $this->calculatorService->calculateCommission('demo-file-path');
        $this->assertEquals(0,count($this->calculatorService->getCommissions()));
    }

    public function testCalculationWhenLookupIsNotEmpty(): void
    {
        $this->calculatorService = $this->getMockBuilder(Calculator::class)
            ->setMethods(['readFile','getLookupValue','getExchangeRates'])
            ->getMock();
        $this->calculatorService->method('readFile')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $this->calculatorService->method('getLookupValue')->willReturn("DK");
        $this->calculatorService->method('getExchangeRates')->willReturn(0);
        $this->calculatorService->calculateCommission('demo-file-path');
        $this->assertEquals(1,$this->calculatorService->getCommissions()[0]);


    }

    public function testCalculationWhenExchangeRateIsZero(): void
    {
        $this->calculatorService = $this->getMockBuilder(Calculator::class)
            ->setMethods(['readFile','getLookupValue','getExchangeRates'])
            ->getMock();
        $this->calculatorService->method('readFile')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $this->calculatorService->method('getLookupValue')->willReturn("DK");
        $this->calculatorService->method('getExchangeRates')->willReturn(0);
        $this->calculatorService->calculateCommission('demo-file-path');
        $this->assertEquals(1,$this->calculatorService->getCommissions()[0]);
    }

    public function testCalculationWhenCurrencyIsNotEur(): void
    {
        $this->calculatorService = $this->getMockBuilder(Calculator::class)
            ->setMethods(['readFile','getLookupValue','getExchangeRates'])
            ->getMock();
        $this->calculatorService->method('readFile')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"USD"}']);
        $this->calculatorService->method('getLookupValue')->willReturn("DK");
        $this->calculatorService->method('getExchangeRates')->willReturn(1);
        $this->calculatorService->calculateCommission('demo-file-path');
        $this->assertEquals(1,$this->calculatorService->getCommissions()[0]);
    }

    public function testCalculationWhenCountryAlphaIsNotValid(): void
    {
        $this->calculatorService = $this->getMockBuilder(Calculator::class)
            ->setMethods(['readFile','getLookupValue','getExchangeRates'])
            ->getMock();

        $this->calculatorService->method('readFile')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"USD"}']);
        $this->calculatorService->method('getLookupValue')->willReturn("DEMO");
        $this->calculatorService->method('getExchangeRates')->willReturn(1);
        $this->calculatorService->calculateCommission('demo-file-path');
        $this->assertEquals(2.0,$this->calculatorService->getCommissions()[0]);
    }
}
