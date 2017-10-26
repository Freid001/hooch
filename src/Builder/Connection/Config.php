<?php

namespace QueryMule\Builder\Connection;

use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerInterface;
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
     * Database constructor.
     * @param array $configs
     * @param LoggerInterface $logger
     */
    public function __construct(array $configs = [], LoggerInterface $logger)
    {
        $this->configs = $configs;
        $this->logger = $logger;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function setConfig(){}

    public function setConfigFromFile(){}

    /**
     * @param $key
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