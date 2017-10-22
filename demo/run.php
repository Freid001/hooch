<?php

namespace QueryMule\Demo;

use QueryMule\Demo\Controller\DemoController;

require(__DIR__.'/../vendor/autoload.php');

array_shift($argv);

$method = null;
if(isset($argv[0])) {
    $method = $argv[0];
    unset($argv[0]);
}

$controller = new DemoController();

switch ($method)
{
    case "get":
        echo $controller->getBook(isset($argv[1]) ? $argv[1] : null) . "\n";
        break;

    case "post":
        break;

    case "put":
        break;

    case "delete":
        break;

    default:
        echo "Usage:\n";
        echo "\tcommand [argument]\n\n";
        echo "Commands: \n";
        echo "\tget\t Get a book.\n";
        echo "\tpost\t Update a book.\n";
        echo "\tput\t Add a book.\n";
        echo "\tdelete\t Remove a book.\n";
        break;
}