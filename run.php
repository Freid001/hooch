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

class Book implements TableInterface{

    /**
     * @return string
     */
    public function getTableName()
    {
        return 'book';
    }
}

$table = new Book();

$handler = $database->dbh('query_mule')->conn();
$query = $handler->select()->cols(['book_name'=>'name','id'],'b')->from($table,'b')->build();
$result = $handler->execute($query)->fetchAll();

var_dump($query->sql());
var_dump($result);
















