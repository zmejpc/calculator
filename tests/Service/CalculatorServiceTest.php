<?php

namespace App\Tests\Unit\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Contract\CurrencyRatesProviderInterface;
use App\Contract\BinProviderInterface;
use App\Service\CalculatorService;
use App\Entity\Transaction;

class CalculatorServiceTest extends KernelTestCase
{
	private CurrencyRatesProviderInterface $currencyRatesProvider;

	private BinProviderInterface $binProviderInterface;

	private CalculatorService $calculatorService;

	protected function setUp(): void
    {
        parent::setUp();

        $config = static::getContainer()->getParameter('calculator');

        $this->currencyRatesProvider = $this->createMock(CurrencyRatesProviderInterface::class);
        $this->binProviderInterface = $this->createMock(BinProviderInterface::class);

        $this->calculatorService = new CalculatorService($this->currencyRatesProvider, $this->binProviderInterface, $config);
    }

	public function testEuCountryAndDefaultCurrency(): void
    {
    	$transaction = new Transaction;
		$transaction->setBin('45717360');
		$transaction->setCurrency('EUR');
		$transaction->setAmount(100);

		$this->binProviderInterface->expects($this->once())
            ->method('getCountryCode')
            ->with($transaction->getBin())
            ->willReturn('DK');

		$result = $this->calculatorService->calc([$transaction]);

        self::assertEquals($result[0], 1);
    }

    public function testEuCountryAndNotDefaultCurrency(): void
    {
    	$transaction = new Transaction;
		$transaction->setBin('45717360');
		$transaction->setCurrency('GBP');
		$transaction->setAmount(100);

		$this->binProviderInterface->expects($this->once())
            ->method('getCountryCode')
            ->with($transaction->getBin())
            ->willReturn('DK');

        $this->currencyRatesProvider->expects($this->once())
            ->method('getRate')
            ->with($transaction->getCurrency())
            ->willReturn(0.9);

		$result = $this->calculatorService->calc([$transaction]);

        self::assertEquals($result[0], 1.11);
    }

    public function testNotEuCountryAndDefaultCurrency(): void
    {
    	$transaction = new Transaction;
		$transaction->setBin('45717360');
		$transaction->setCurrency('EUR');
		$transaction->setAmount(100);

		$this->binProviderInterface->expects($this->once())
            ->method('getCountryCode')
            ->with($transaction->getBin())
            ->willReturn('AS');

		$result = $this->calculatorService->calc([$transaction]);

        self::assertEquals($result[0], 2);
    }

    public function testNonEuCountryAndNotDefaultCurrency(): void
    {
    	$transaction = new Transaction;
		$transaction->setBin('45717360');
		$transaction->setCurrency('GBP');
		$transaction->setAmount(100);

		$this->binProviderInterface->expects($this->once())
            ->method('getCountryCode')
            ->with($transaction->getBin())
            ->willReturn('AS');

        $this->currencyRatesProvider->expects($this->once())
            ->method('getRate')
            ->with($transaction->getCurrency())
            ->willReturn(0.9);

		$result = $this->calculatorService->calc([$transaction]);

        self::assertEquals($result[0], 2.22);
    }
}