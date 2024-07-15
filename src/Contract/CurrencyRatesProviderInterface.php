<?php

namespace App\Contract;

interface CurrencyRatesProviderInterface
{
	public function getRate(string $curr_code): float;
}