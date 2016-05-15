<?php
date_default_timezone_set('UTC');

require "../vendor/autoload.php";

$application = new \LoT\Application\Application;
$application->ingestConfigFromDirectory(realpath(__DIR__ . '/../config'));
$application->run();
