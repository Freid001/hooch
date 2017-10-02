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

class Book implements TableInterface
{
    private $select;
    private $filter;

    public function __construct(\QueryMule\Query\Connection\Driver\DriverInterface $driver)
    {
        $this->select = $driver->select();
        $this->filter = $driver->filter();
    }

    public function getTableName()
    {
        return 'book';
    }

    public function getFilter() : \QueryMule\Query\Sql\Statement\FilterInterface
    {
        return $this->filter;
    }

    public function select() : \QueryMule\Query\Sql\Statement\SelectInterface
    {
        return $this->select->cols(['book_name'=>'name','id'],'b')->from($this,'b');
    }

    public function filterId($id) : \QueryMule\Query\Sql\Statement\FilterInterface
    {
        return $this->filter->where(function (\QueryMule\Query\Sql\Statement\FilterInterface $filter) use ($id) {
            $filter->where('b.id', '=?', $id);
        });
    }
}

$driver = $database->dbh('query_mule_mysql')->driver();

$table = new Book($driver);
$table->filterId(1);
$table->filterId(1);

$query = $driver->select()->cols(['book_name'=>'name','id'],'b')
    ->from($table,'b')
    ->where(function(\QueryMule\Query\Sql\Statement\FilterInterface $query){
        $query->where('id','> ?',1);
    })
    ->build();

//$result = $driver->fetchAll($query);

var_dump($query->sql());
//var_dump($result);

//note: presto
