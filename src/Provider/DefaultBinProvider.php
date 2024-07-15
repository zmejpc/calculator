<?php

namespace App\Provider;

use App\Contract\BinProviderInterface;

class DefaultBinProvider implements BinProviderInterface
{
	public function getCountryCode(string $card): string
	{
		return @json_decode(file_get_contents('https://api.exchangeratesapi.io/latest'), true)['rates'][$card];
	}
}