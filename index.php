<?php

use App\Calculator;

require_once "./vendor/autoload.php";

(new Calculator())->process($argv[1]);