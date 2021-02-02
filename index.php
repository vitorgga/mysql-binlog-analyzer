<?php
require __DIR__.'/vendor/autoload.php';

use App\Command\MysqlBinlogAnalyzer;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new MysqlBinlogAnalyzer());

$application->run();