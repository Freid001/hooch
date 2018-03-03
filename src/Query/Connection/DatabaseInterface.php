<?php

namespace QueryMule\Query\Connection;

use QueryMule\Query\Connection\Handler\DatabaseHandlerInterface;

/**
 * Interface DatabaseInterface
 * @package QueryMule\Query\Connection
 */
interface DatabaseInterface
{
    /**
     * @param string $key
     * @return DatabaseHandlerInterface
     */
    public function dbh($key) : DatabaseHandlerInterface;
}
