<?php

use App\Kernel;

require_once dirname(__DIR__).'/vendor/autoload_runtime.php';

$kernel = new Kernel('dev', false);
$kernel->boot();

$controller = $kernel->getContainer()->get('App\\Controller\\DefaultController');
$controller->run();