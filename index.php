<?php

use App\Calculator;

require_once "./vendor/autoload.php";

(new Calculator())->calculateCommission(!empty($argv[1]) ? $argv[1] : null)->printCommissions();