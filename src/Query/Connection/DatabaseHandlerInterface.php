<?php

namespace QueryMule\Query\Connection;

use QueryMule\Query\Connection\Driver\DriverInterface;

/**
 * Interface DatabaseHandlerInterface
 * @package QueryMule\Query\Connection
 */
interface DatabaseHandlerInterface
{
    /**
     * @return DriverInterface
     */
    public function driver() : DriverInterface;
}