<?php namespace freidcreations\QueryMule\Query\Connection;

/**
 * @name AbstractDatabase
 * @author Fraser Reid
 * @created 26/11/2016
 * @copyright Copyright (c) - 2016 Fraser Reid
 */
abstract class AbstractDatabase
{
    const DRIVE_POST_GRE_SQL = 'pgsql';
    const DRIVE_MYSQL = 'mysql';
    const DRIVE_SQLITE = 'sqlite';

    /**
     * @var string
     */
    protected static $active;

    /**
     * @var bool
     */
    protected static $stick = false;

    /**
     * @var array
     */
    private static $db = [];

    /**
     * Data base handler
     * @return \PDO
     */
    public static function dbh()
    {
        if(!isset(self::$db[self::$active]) ||
            (isset(self::$db[self::$active]) && is_null(self::$db[self::$active]))){
            self::createConnection(self::$active);
        }
        return self::$db[self::$active];
    }

    /**
     * Switch connection
     *
     * @param $configName
     * @param $stick
     * @return $this
     */
    public function switchConnection($configName, $stick = false)
    {
        self::$active = $configName;
        self::$stick = $stick;
        return $this;
    }

    /**
     * Close connection
     *
     * @param $configName
     * @return $this
     * @throws \Exception
     */
    public function closeConnection($configName)
    {
        if(isset(self::$db[$configName])) {
            unset(self::$db[$configName]);

            //Fetch project connections
            $connections = [];
            if(method_exists("\\QueryMule\\Connections", "database")) {
                $connections = call_user_func_array( [ "\\QueryMule\\Connections", "database" ], [] );
            }

            //Fetch default database
            $default = null;
            foreach($connections as $key => $connection){
                if($connections=='default'){
                    $default = $key;
                }
            }

            //Throw exception if we have not set a default database
            if(is_null($default)){
                throw new \Exception("Default database not found!");
            }

            //Set the active connection to default
            self::$active = $default;
            self::$stick = false;
        }
        return $this;
    }

    /**
     * Active Drive
     */
    public function activeDrive()
    {
        return self::dbh()->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Create Connection
     * @param $config
     * @return \PDO
     * @throws \Exception
     */
    private function createConnection($config)
    {
        try{
            //Fetch project connections
            $connections = [];
            if(method_exists("\\QueryMule\\Connections", "database")) {
                $connections = call_user_func_array( [ "\\QueryMule\\Connections", "database" ], [ ] );
            }

            //Does this config exist?
            if(array_key_exists($config, $connections)){
                self::$db[$config] = new \pdo( $connections[$config]['driver'] . ":host=".$connections[$config]['host']."; dbname=".$connections[$config]['database']."", $connections[$config]['username'], $connections[$config]['password']);
            }else {
                throw new \Exception( $config . ' is not a valid DB connection' );
            }
        }catch(\PDOException $e){
            throw new \Exception( "Connection failed: " . $e->getMessage() );
        }
        return self::$db[$config];
    }
}