<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Connection\Handler;

use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;

/**
 * Interface DatabaseHandlerInterface
 * @package Redstraw\Hooch\Query\Connection
 */
interface DatabaseHandlerInterface
{
    /**
     * @return DriverInterface [description]
     */
    public function driver() : DriverInterface;
}
