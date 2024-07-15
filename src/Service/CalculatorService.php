<?php

namespace App\Service;

use App\Contract\CurrencyRatesProviderInterface;
use App\Contract\BinProviderInterface;

class CalculatorService
{
	function __construct(
		private CurrencyRatesProviderInterface $currencyRatesProvider,
		private BinProviderInterface $binProviderInterface,
		private array $config,
	) {}

	public function calc(array $transactions): array
	{
		foreach ($transactions as $transaction) {

			if ($transaction->getCurrency() != $this->config['default_currency']) {
				$rate = $this->currencyRatesProvider->getRate($transaction->getCurrency());

				$comm_amount = $transaction->getAmount() / $rate;

			} else {
				$comm_amount = $transaction->getAmount();
			}

			$country_code = $this->binProviderInterface->getCountryCode($transaction->getBin());

			if (in_array($country_code, $this->config['eu_countries'])) {
				$comm_amount *= $this->config['eu_percent'];
			} else {
				$comm_amount *= $this->config['non_eu_percent'];
			}

			$result[] = round($comm_amount, 2);
		}

		return $result ?? [];
	}
}