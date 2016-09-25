<?php namespace freidcreations\QueryMule\Query\Connection;

/**
 * Interface DatabaseInterface
 * @package freidcreations\QueryMule\Query\Connection
 */
interface DatabaseInterface
{
    /**
     * Database constructor.
     * @param $driver
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     */
    public function __construct($driver,$host,$database,$username,$password);

    /**
     * Data base handler
     * @param $databaseConnectionKey
     * @return \PDO
     */
    public static function dbh($databaseConnectionKey);

    /**
     * Connection
     * @return mixed
     */
    public function connection();

    /**
     * Driver
     */
    public function driver();

    /**
     * Change
     * @param $configName
     * @param $stick
     * @return $this
     */
    public function change($configName, $stick = false);

    /**
     * Close
     * @param $configName
     * @return $this
     * @throws \Exception
     */
    public function close($configName);
}