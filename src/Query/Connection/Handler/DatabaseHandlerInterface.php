<?php

namespace QueryMule\Query\Connection\Handler;

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
