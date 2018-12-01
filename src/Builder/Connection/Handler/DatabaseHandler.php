<?php

namespace QueryMule\Builder\Connection\Handler;

use Psr\Log\LoggerInterface;
use QueryMule\Builder\Connection\Driver\MysqliDriver;
use QueryMule\Builder\Connection\Driver\PdoDriver;
use QueryMule\Builder\Exception\DatabaseException;
use QueryMule\Builder\Exception\DriverException;
use QueryMule\Query\Connection\Handler\DatabaseHandlerInterface;
use QueryMule\Query\Connection\Driver\DriverInterface;

/**
 * Class DatabaseHandler
 * @package QueryMule\Builder\Connection
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
     * @var LoggerInterface
     */
    private $logger;

    /**
     * DatabaseHandler constructor.
     * @param string $name
     * @param array $dbh
     * @param LoggerInterface $logger
     * @throws DatabaseException
     * @throws DriverException
     */
    public function __construct($name,array $dbh = [],LoggerInterface $logger)
    {
        //Validate config
        foreach([
            self::DATABASE_DRIVER,
            self::DATABASE_ADAPTER
                ] as $key){
            if(empty($dbh[$key])){
                throw new DatabaseException(sprintf("%u not set in config: %u",$key,$name));
            }
        }

        //Set logger
        $this->logger = $logger;

        //Select database adapter
        switch ($dbh[self::DATABASE_ADAPTER]){
            case self::ADAPTER_PDO:
                switch($dbh[self::DATABASE_DRIVER]){
                    case 'mysql':
                        $dns = ":host=".$dbh[self::DATABASE_HOST]."; dbname=".$dbh[self::DATABASE_DATABASE];
                        break;

                    case 'sqlite':
                        $dns = ":".$dbh[self::DATABASE_PATH_TO_FILE];
                        break;

                    default:
                        throw new DriverException('Driver unavailable!');
                }

                $username = !empty($dbh[self::DATABASE_USER]) ? $dbh[self::DATABASE_USER] : null;
                $password = !empty($dbh[self::DATABASE_PASSWORD]) ? $dbh[self::DATABASE_PASSWORD] : null;

                $this->driver = new PdoDriver(new \PDO($dbh[self::DATABASE_DRIVER] . $dns,$username,$password), $this->logger);
                break;

            case self::ADAPTER_MYSQLI:
                $this->driver = new MysqliDriver(new \mysqli($dbh[self::DATABASE_HOST],$dbh[self::DATABASE_USER], $dbh[self::DATABASE_PASSWORD], $dbh[self::DATABASE_DATABASE]), $this->logger);
                break;

            default:
                throw new DatabaseException("Adapter not found for: " . $dbh[self::DATABASE_ADAPTER]);
        }
    }

    /**
     * @return DriverInterface
     */
    public function driver() : DriverInterface
    {
        return $this->driver;
    }
}
