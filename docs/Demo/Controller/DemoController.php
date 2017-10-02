<?php

namespace QueryMule\Demo\Controller;

use QueryMule\Builder\Connection\Database;
use QueryMule\Builder\Connection\DatabaseHandler;
use QueryMule\Demo\Table\Book;

/**
 * Class Demo
 * @package QueryMule\Demo
 */
class DemoController
{
    /**
     * @var \QueryMule\Query\Connection\Driver\DriverInterface
     */
    private $driver;

    /**
     * Demo constructor.
     */
    public function __construct()
    {
        $database = new Database([
            'sqlite' => [
                DatabaseHandler::DATABASE_DRIVER => 'sqlite',
                DatabaseHandler::DATABASE_DATABASE => 'sqlite.db',
                DatabaseHandler::DATABASE_PATH_TO_FILE => __DIR__.'/../Database/sqlite.db',
                DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO,
            ]
        ]);

        $this->driver = $database->dbh('sqlite')->driver();
    }

    /**
     * @param $book_id
     * @return string
     */
    public function getBook($book_id)
    {
        if(empty($book_id)){
            return json_encode(['error'=>'book_id is required!'], JSON_PRETTY_PRINT);
        }

        $book = new Book($this->driver);
        $book->filterByBookId($book_id);

        $query = $book->select()->build();

        $result = $this->driver->fetchAll($query);

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    public function postBook(){}

    public function putBook(){}

    public function deleteBook(){}
}