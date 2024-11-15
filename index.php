<?php

require_once __DIR__ . '/local-settings.php';
require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . '/src/utility.php';

use Src\Kernel;

(new Kernel)->run();
