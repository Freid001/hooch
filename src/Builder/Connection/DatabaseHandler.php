<?php

namespace QueryMule\Builder\Connection;

use QueryMule\Builder\Adapter\MysqliAdapter;
use QueryMule\Builder\Adapter\PdoAdapter;
use QueryMule\Builder\Exception\DatabaseException;
use QueryMule\Query\Adapter\AdapterInterface;
use QueryMule\Query\Connection\DatabaseHandlerInterface;

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
     * @var \pdo
     */
    private $adapter;

    /**
     * DatabaseHandler constructor.
     * @param $name
     * @param array $dbh
     * @throws DatabaseException
     */
    public function __construct($name,array $dbh)
    {
        //Validate config
        foreach([
            self::DATABASE_DRIVER,
            self::DATABASE_ADAPTER
                ] as $key){
            if(empty($dbh[$key])){
                throw new DatabaseException($key." not set in config: ".$name);
            }
        }

        //Select database adapter
        switch ($dbh[self::DATABASE_ADAPTER]){
            case self::ADAPTER_PDO:

                $dns = null;
                if(!empty($dbh[self::DATABASE_HOST]) && !empty($dbh[self::DATABASE_DATABASE])){
                    $dns = ":host=".$dbh[self::DATABASE_HOST]."; dbname=".$dbh[self::DATABASE_DATABASE];
                }else if(!empty($dbh[self::DATABASE_PATH_TO_FILE])){
                    $dns = ":".$dbh[self::DATABASE_PATH_TO_FILE];
                }


                $this->adapter = new PdoAdapter(new \pdo($dbh[self::DATABASE_DRIVER] . $dns)); // $dbh[self::DATABASE_USER], $dbh[self::DATABASE_PASSWORD]));
                break;

            case self::ADAPTER_MYSQLI:
                $this->adapter = new MysqliAdapter(new \mysqli($dbh[self::DATABASE_HOST],$dbh[self::DATABASE_USER], $dbh[self::DATABASE_PASSWORD], $dbh[self::DATABASE_DATABASE]));
                break;

            default:
                throw new DatabaseException("Adapter not found for: " . $dbh[self::DATABASE_ADAPTER]);
        }
    }

    /**
     * @return AdapterInterface
     */
    public function conn() : AdapterInterface
    {
        return $this->adapter;
    }
}