<?php

namespace QueryMule\Builder\Connection;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use QueryMule\Builder\Connection\Handler\DatabaseHandler;
use QueryMule\Query\Connection\Handler\DatabaseHandlerInterface;
use QueryMule\Query\Connection\DatabaseInterface;
use QueryMule\Builder\Exception\DatabaseException;

/**
 * Class Database
 * @package QueryMule\Builder\Connection
 */
class Config implements DatabaseInterface, LoggerAwareInterface
{
    /**
     * @var array
     */
    private $configs = [];

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * Config constructor.
     */
    public function __construct()
    {
        $this->logger = new NullLogger();
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * @param array $configs
     */
    public function setConfigs(array $configs = [])
    {
        $this->configs = $configs;
    }

    /**
     * @param string $key
     * @return DatabaseHandlerInterface
     * @throws DatabaseException
     */
    public function dbh($key) : DatabaseHandlerInterface
    {
        if (!empty($this->configs[$key])) {
            return new DatabaseHandler($key, $this->configs[$key], $this->logger);
        }

        throw new DatabaseException("Could not find: " . $key . ", did you forget to register the config?");
    }
}
