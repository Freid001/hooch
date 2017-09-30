<?php

namespace QueryMule\Query\Table;

use QueryMule\Query\Connection\Driver\DriverInterface;

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
}