<?php namespace freidcreations\QueryMule\Builder\Sql;
use freidcreations\QueryMule\Builder\Connection\Database;

/**
 * Class Table
 * @package freidcreations\QueryMule\Builder\Sql
 */
class Table
{
    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $databaseConnectionKey;

    /**
     * @var null
     */
    private $from = null;

    /**
     * Table constructor.
     * @param $databaseConnectionKey
     * @param $name
     */
    public function __construct($databaseConnectionKey,$name)
    {
        $this->databaseConnectionKey = $databaseConnectionKey;
        $this->table = $name;
    }

    /**
     * Database Handler
     * @return Database
     */
    public function dbh()
    {
        return Database::dbh($this->databaseConnectionKey);
    }

    /**
     * From
     * @param $sql
     * @return $this;
     */
    public function from($sql)
    {
        $this->from = $sql;
        return $this;
    }

    /**
     * Table Name
     * @return mixed
     */
    public function name()
    {
        return !is_null( $this->from ) ? '( ' . $this->from . ' ) AS ' . $this->table : $this->table;
    }
}