<?php

require('vendor/autoload.php');

use QueryMule\Builder\Connection\Database;
use QueryMule\Builder\Connection\DatabaseHandler;
use QueryMule\Query\Table\TableInterface;

$database = new Database([
    'query_mule_mysql' => [
        DatabaseHandler::DATABASE_DRIVER => 'mysql',
        DatabaseHandler::DATABASE_HOST => '127.0.0.1',
        DatabaseHandler::DATABASE_DATABASE => 'query_mule',
        DatabaseHandler::DATABASE_USER => 'root',
        DatabaseHandler::DATABASE_PASSWORD => '123',
        DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO
    ],
    'query_mule_pgsql' => [
        DatabaseHandler::DATABASE_DRIVER => 'pgsql',
        DatabaseHandler::DATABASE_HOST => '127.0.0.1',
        DatabaseHandler::DATABASE_DATABASE => 'query_mule',
        DatabaseHandler::DATABASE_USER => 'root',
        DatabaseHandler::DATABASE_PASSWORD => '123',
        DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO,
    ],
    'query_mule_sqlite' => [
        DatabaseHandler::DATABASE_DRIVER => 'sqlite',
        DatabaseHandler::DATABASE_DATABASE => 'query_mule',
        DatabaseHandler::DATABASE_PATH_TO_FILE => __DIR__.'/sqlite.db',
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

$handler = $database->dbh('query_mule_sqlite')->conn();

$query = $handler->select()->cols(['book_name'=>'name','id'],'b')
    ->from($table,'b')
    ->where(function(\QueryMule\Query\Sql\Statement\SelectInterface $query) {
        $query->where(function(\QueryMule\Query\Sql\Statement\SelectInterface $query) {
            $query->where(function(\QueryMule\Query\Sql\Statement\SelectInterface $query) {
                $query->where(function (\QueryMule\Query\Sql\Statement\SelectInterface $query) {
                    $query->where('id', '=?', 1);
                });
            });
            $query->orWhere(function (\QueryMule\Query\Sql\Statement\SelectInterface $query) {
                $query->where('id', '=?', 1);
            });
            $query->where(function (\QueryMule\Query\Sql\Statement\SelectInterface $query) {
                $query->where('id', '=?', 1);
            });
        });
    })->build();

$result = $handler->fetchAll($query);

var_dump($query->sql());
var_dump($result);











//note: presto