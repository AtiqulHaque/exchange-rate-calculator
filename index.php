<?php

use App\Calculator;

require_once "./vendor/autoload.php";

(new Calculator())->process(!empty($argv[1]) ? $argv[1] : null)->printAmounts();