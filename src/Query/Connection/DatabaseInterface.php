<?php namespace QueryMule\Query\Connection;

/**
 * Interface DatabaseInterface
 * @package QueryMule\Query\Connection
 */
interface DatabaseInterface
{
    /**
     * @param $key
     * @return DatabaseHandlerInterface
     */
    public function dbh($key) : DatabaseHandlerInterface;
}