<?php

namespace QueryMule\Demo;

use QueryMule\Demo\Controller\DemoController;

require(__DIR__.'/../../vendor/autoload.php');

array_shift($argv);

if(empty($argv)){
    echo json_encode(['error'=>'Invalid methods!'],JSON_PRETTY_PRINT) . "\n";
    exit;
}

$method = $argv[0];

unset($argv[0]);

$controller = new DemoController();

switch ($method)
{
    case "get":
        echo $controller->getBook(isset($argv[1]) ? $argv[1] : null) . "\n";
        break;

    default:
        echo json_encode(['error'=>'Invalid methods!'],JSON_PRETTY_PRINT) . "\n";
        break;
}