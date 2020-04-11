<?php

use App\Calculator;
use App\Reader\FileReader;
use App\Services\BinLookUp;
use App\Services\ExchangeRate;

require_once "./vendor/autoload.php";
$argument = !empty($argv[1]) ? $argv[1] : null;

(new Calculator())
    ->calculateCommission($argument, new FileReader(), new BinLookUp(), new ExchangeRate())
    ->printCommissions();