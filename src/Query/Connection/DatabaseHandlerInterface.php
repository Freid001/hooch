<?php namespace QueryMule\Query\Connection;
use QueryMule\Query\Adapter\AdapterInterface;

/**
 * Interface DatabaseHandlerInterface
 * @package QueryMule\Query\Connection
 */
interface DatabaseHandlerInterface
{
    /**
     * @return AdapterInterface
     */
    public function conn() : AdapterInterface;
}