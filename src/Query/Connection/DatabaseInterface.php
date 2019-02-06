<?php

namespace Redstraw\Hooch\Query\Connection;

use Redstraw\Hooch\Query\Connection\Handler\DatabaseHandlerInterface;

/**
 * Interface DatabaseInterface
 * @package Redstraw\Hooch\Query\Connection
 */
interface DatabaseInterface
{
    /**
     * @param string $key
     * @return DatabaseHandlerInterface
     */
    public function dbh($key) : DatabaseHandlerInterface;
}
