<?php
use App\Email;
use PHPUnit\Framework\TestCase;

final class CalculatorTest extends TestCase
{
    public function testFileRead(): void
    {
        $this->calculatorService = $this->getMockBuilder(\App\Calculator::class)
            ->setMethods(['readFile'])->getMock();
        //$this->calculatorService->method('readFile')->willReturn(['{"bin":"45717360","amount":"100.00","currency":"EUR"}']);
        $this->calculatorService->method('readFile')->willReturn([]);
        $this->calculatorService->process('demo-path');

        var_dump($this->calculatorService->amounts);die;
    }
}
