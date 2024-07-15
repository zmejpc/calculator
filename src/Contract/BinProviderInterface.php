<?php

namespace App\Contract;

interface BinProviderInterface
{
	public function getCountryCode(string $card): string;
}