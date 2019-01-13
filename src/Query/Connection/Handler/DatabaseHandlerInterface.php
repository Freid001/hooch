<?php

declare(strict_types=1);

namespace QueryMule\Query\Connection\Handler;

use QueryMule\Query\Connection\Driver\DriverInterface;

/**
 * Interface DatabaseHandlerInterface
 * @package QueryMule\Query\Connection
 */
interface DatabaseHandlerInterface
{
    /**
     * @return DriverInterface [description]
     */
    public function driver() : DriverInterface;
}
