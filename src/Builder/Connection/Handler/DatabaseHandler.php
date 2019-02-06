<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Connection\Handler;


use Redstraw\Hooch\Query\Connection\Handler\DatabaseHandlerInterface;
use Redstraw\Hooch\Query\Connection\Driver\DriverInterface;

/**
 * Class DatabaseHandler
 * @package Redstraw\Hooch\Builder\Connection
 */
class DatabaseHandler implements DatabaseHandlerInterface
{
    const ADAPTER_PDO           = 'pdo';
    const ADAPTER_MYSQLI        = 'mysqli';

    const DATABASE_ADAPTER      = 'adapter';
    const DATABASE_DRIVER       = 'driver';
    const DATABASE_DATABASE     = 'database';
    const DATABASE_PATH_TO_FILE = 'path_to_file';
    const DATABASE_HOST         = 'host';
    const DATABASE_PASSWORD     = 'password';
    const DATABASE_USER         = 'user';

    /**
     * @var DriverInterface
     */
    private $driver;

    /**
     * @param DriverInterface $driver
     */
    public function __construct(DriverInterface $driver)
    {
        $this->driver = $driver;
    }

    /**
     * [driver description]
     * @return DriverInterface [description]
     */
    public function driver() : DriverInterface
    {
          return $this->driver;
    }
}
