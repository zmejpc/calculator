<?php

namespace App\Provider;

use App\Contract\CurrencyRatesProviderInterface;

class DefaultCurrencyRateProvider implements CurrencyRatesProviderInterface
{
	public function getRate(string $curr_code): float
	{
		return @json_decode(file_get_contents('https://api.exchangeratesapi.io/latest'), true)['rates'][$curr_code];
	}
}