<?php

//Determine vendor path
if(file_exists(__DIR__.'/../../../../vendor/autoload.php')){
    $path = __DIR__.'/../../../../';
}else {
    $path = __DIR__.'/../';
}

//Require composer autoloader
require_once($path.'vendor/autoload.php');

//QueryMule Console
$console = new \freidcreations\QueryMule\Builder\Console\Console($path);

//Run Command
$console->run($argv);

