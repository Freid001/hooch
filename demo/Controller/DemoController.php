<?php

namespace QueryMule\Demo\Controller;

use QueryMule\Builder\Connection\Database;
use QueryMule\Builder\Connection\DatabaseHandler;
use QueryMule\Demo\Table\Author;
use QueryMule\Demo\Table\Book;
use QueryMule\Query\Sql\Statement\SelectInterface;

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
                DatabaseHandler::DATABASE_PATH_TO_FILE => __DIR__ . '/../Database/sqlite.db',
                DatabaseHandler::DATABASE_ADAPTER => DatabaseHandler::ADAPTER_PDO,
            ]
        ]);

        $this->driver = $database->dbh('sqlite')->driver();
    }

    /**
     * @param $book_id
     * @param null $author
     * @return string
     */
    public function getBook($book_id, $author = null)
    {
        $author = new Author($this->driver);

        $book = new Book($this->driver);
        $query = $this->driver->select()->cols([SelectInterface::SQL_STAR])->from($book,'b');
        $query->leftJoin(['a'=>$author],'a.author_id','=','b.author_id');

        $stm = $query->build();

        $result = $this->driver->fetch($stm);

        var_dump($stm);
        var_dump($result);

        die;

        if(empty($book_id)){
            return json_encode(['error'=>'book_id is required!'], JSON_PRETTY_PRINT);
        }

        $book = new Book($this->driver);

        $query = $book->select([SelectInterface::SQL_STAR],'b');

        if($author){
            $book->joinAuthor();
        }

        $book->filterByBookId($book_id);

        $result = $this->driver->fetchAll($query->build());

        return json_encode($result, JSON_PRETTY_PRINT);
    }

    public function postBook(){}

    public function putBook(){}

    public function deleteBook(){}
}