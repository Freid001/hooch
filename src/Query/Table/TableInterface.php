<?php

namespace QueryMule\Query\Table;

use QueryMule\Query\Connection\Driver\DriverInterface;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface TableInterface
 * @package QueryMule\Query\Table
 */
interface TableInterface
{
    /**
     * TableInterface constructor.
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver);

    /**
     * @return string
     */
    public function getTableName();

    /**
     * @return FilterInterface
     */
    public function getFilter() : FilterInterface;
}