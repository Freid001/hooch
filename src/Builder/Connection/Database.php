<?php

namespace QueryMule\Builder\Connection;

use QueryMule\Query\Connection\DatabaseHandlerInterface;
use QueryMule\Query\Connection\DatabaseInterface;
use QueryMule\Builder\Exception\DatabaseException;

/**
 * Class Database
 * @package QueryMule\Builder\Connection
 */
class Database implements DatabaseInterface
{
    /**
     * @var array
     */
    private $configs = [];

    /**
     * Database constructor.
     * @param array $configs
     */
    public function __construct(array $configs)
    {
        $this->configs = $configs;
    }

    /**
     * @param $key
     * @return DatabaseHandlerInterface
     * @throws DatabaseException
     */
    public function dbh($key) : DatabaseHandlerInterface
    {
        if (!empty($this->configs[$key])) {
            return new DatabaseHandler($key, $this->configs[$key]);
        }

        throw new DatabaseException("Could not find config: " . $key . " did you forget to register this database config?");
    }
}