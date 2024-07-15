<?php

namespace App\Service;

use App\Entity\Transaction;

class TransactionService
{
	public function __construct(private string $public_dir) {}

	public function loadTransactions()
	{
		$data = file_get_contents($this->public_dir . '/transactions.txt');

		foreach (explode("\n", $data) as $row) {
			$item = json_decode($row, true);

			if (is_array($item)) {

				$transaction = new Transaction;

				$transaction->setBin($item['bin']);
				$transaction->setAmount($item['amount']);
				$transaction->setCurrency($item['currency']);

				$transactions[] = $transaction;
			}
		}

		return $transactions ?? [];
	}	
}