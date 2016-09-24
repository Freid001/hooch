<?php require_once(dirname(__FILE__).'/../../../../vendor/autoload.php');

//QueryMule Console
$console = new \freidcreations\QueryMule\Builder\Console\Console();

//Run Command
$console->run($argv);

