<?php

require('vendor/autoload.php');

use QueryMule\Builder\Connection\Database;
use QueryMule\Builder\Connection\DatabaseHandler;
use QueryMule\Query\Table\TableInterface;

$database = new Database([
    'query_mule' => [
        DatabaseHandler::DATABASE_DRIVER => 'mysql',
        DatabaseHandler::DATABASE_HOST => '127.0.0.1',
        DatabaseHandler::DATABASE_DATABASE => 'query_mule',
        DatabaseHandler::DATABASE_USER => 'root',
        DatabaseHandler::DATABASE_PASSWORD => '123',
        DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO,
    ]
]);

class TestTable implements TableInterface{

    /**
     * @return string
     */
    public function getTableName() : string
    {
        return 'test_table';
    }
}

$table = new TestTable();

$handler = $database->dbh('query_mule');
$query = $handler->conn()->select()->cols(['table'=>'test','cheese'],'t')->from($table)->build();






var_dump($query);