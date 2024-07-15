<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\TransactionService;
use App\Service\CalculatorService;

class DefaultController extends AbstractController
{
	public function __construct(
		private TransactionService $transactionService,
		private CalculatorService $calculator
	) {}

	#[Route('/', name: 'default')]
	public function run()
	{
		$transactions = $this->transactionService->loadTransactions();

		$result = $this->calculator->calc($transactions);

		dump($result);
		
		exit('done');
	}
}
