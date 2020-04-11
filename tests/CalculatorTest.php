<?php
use App\Calculator;
use App\Reader\FileReader;
use App\Services\BinLookUp;
use App\Services\ExchangeRate;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function testFileReadWhenEmpty(): void
    {
        $fileReader =   $this->getMockBuilder(FileReader::class)
            ->setMethods(['read'])
            ->getMock();

        $binLookUp =   $this->getMockBuilder(BinLookUp::class)
            ->setMethods(['getLookUpValue'])
            ->getMock();

        $exchangeRate =   $this->getMockBuilder(ExchangeRate::class)
            ->setMethods(['getRate'])
            ->getMock();

        $fileReader->method('read')->willReturn([]);

        $calculatorService = new Calculator();

        $calculatorService->calculateCommission('demo-file-path',$fileReader,$binLookUp,$exchangeRate);

        $this->assertEquals(0,count($calculatorService->getCommissions()));
    }

    public function testCalculationWhenLookupIsEmpty(): void
    {

        $fileReader =   $this->getMockBuilder(FileReader::class)
            ->setMethods(['read'])
            ->getMock();

        $binLookUp =   $this->getMockBuilder(BinLookUp::class)
            ->setMethods(['getLookUpValue'])
            ->getMock();

        $exchangeRate =   $this->getMockBuilder(ExchangeRate::class)
            ->setMethods(['getRate'])
            ->getMock();

        $fileReader->method('read')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $binLookUp->method('getLookUpValue')->willReturn(false);

        $calculatorService = new Calculator();

        $calculatorService->calculateCommission('demo-file-path',$fileReader,$binLookUp,$exchangeRate);

        $this->assertEquals(0,count($calculatorService->getCommissions()));
    }

    public function testCalculationWhenLookupIsNotEmpty(): void
    {

        $fileReader =   $this->getMockBuilder(FileReader::class)
            ->setMethods(['read'])
            ->getMock();

        $binLookUp =   $this->getMockBuilder(BinLookUp::class)
            ->setMethods(['getLookUpValue'])
            ->getMock();

        $exchangeRate =   $this->getMockBuilder(ExchangeRate::class)
            ->setMethods(['getRate'])
            ->getMock();

        $fileReader->method('read')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $binLookUp->method('getLookUpValue')->willReturn("DK");
        $exchangeRate->method('getRate')->willReturn(0);

        $calculatorService = new Calculator();

        $calculatorService->calculateCommission('demo-file-path',$fileReader,$binLookUp,$exchangeRate);

        $this->assertEquals(1,$calculatorService->getCommissions()[0]);
    }

    public function testCalculationWhenExchangeRateIsZero(): void
    {
        $fileReader =   $this->getMockBuilder(FileReader::class)
            ->setMethods(['read'])
            ->getMock();

        $binLookUp =   $this->getMockBuilder(BinLookUp::class)
            ->setMethods(['getLookUpValue'])
            ->getMock();

        $exchangeRate =   $this->getMockBuilder(ExchangeRate::class)
            ->setMethods(['getRate'])
            ->getMock();

        $fileReader->method('read')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $binLookUp->method('getLookUpValue')->willReturn("DK");
        $exchangeRate->method('getRate')->willReturn(0);

        $calculatorService = new Calculator();

        $calculatorService->calculateCommission('demo-file-path',$fileReader,$binLookUp,$exchangeRate);

        $this->assertEquals(1,$calculatorService->getCommissions()[0]);
    }

    public function testCalculationWhenCurrencyIsNotEur(): void
    {

        $fileReader =   $this->getMockBuilder(FileReader::class)
            ->setMethods(['read'])
            ->getMock();

        $binLookUp =   $this->getMockBuilder(BinLookUp::class)
            ->setMethods(['getLookUpValue'])
            ->getMock();

        $exchangeRate =   $this->getMockBuilder(ExchangeRate::class)
            ->setMethods(['getRate'])
            ->getMock();

        $fileReader->method('read')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $binLookUp->method('getLookUpValue')->willReturn("DK");
        $exchangeRate->method('getRate')->willReturn(1);

        $calculatorService = new Calculator();

        $calculatorService->calculateCommission('demo-file-path',$fileReader,$binLookUp,$exchangeRate);

        $this->assertEquals(1,$calculatorService->getCommissions()[0]);
    }

    public function testCalculationWhenCountryAlphaIsNotValid(): void
    {
        $fileReader =   $this->getMockBuilder(FileReader::class)
            ->setMethods(['read'])
            ->getMock();

        $binLookUp =   $this->getMockBuilder(BinLookUp::class)
            ->setMethods(['getLookUpValue'])
            ->getMock();

        $exchangeRate =   $this->getMockBuilder(ExchangeRate::class)
            ->setMethods(['getRate'])
            ->getMock();

        $fileReader->method('read')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $binLookUp->method('getLookUpValue')->willReturn("DEMO");
        $exchangeRate->method('getRate')->willReturn(1);

        $calculatorService = new Calculator();

        $calculatorService->calculateCommission('demo-file-path',$fileReader,$binLookUp,$exchangeRate);

        $this->assertEquals(2.0,$calculatorService->getCommissions()[0]);
    }

    public function testCalculationWhenLookThrowException(): void
    {

        $fileReader =   $this->getMockBuilder(FileReader::class)
            ->setMethods(['read'])
            ->getMock();

        $binLookUp =   $this->getMockBuilder(BinLookUp::class)
            ->setMethods(['getLookUpValue'])
            ->getMock();

        $exchangeRate =   $this->getMockBuilder(ExchangeRate::class)
            ->setMethods(['getRate'])
            ->getMock();

        $fileReader->method('read')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);

        $binLookUp->method('getLookUpValue')->willThrowException(new \Exception("Expected Exception was thrown"));

        $calculatorService = new Calculator();

        $calculatorService->calculateCommission('demo-file-path',$fileReader,$binLookUp,$exchangeRate);

        $this->assertEquals(0,count($calculatorService->getCommissions()));
    }
}
