<?php namespace freidcreations\QueryMule\Builder\Connection;
use freidcreations\QueryMule\Query\Connection\DatabaseInterface;

/**
 * Class Database
 * @package freidcreations\QueryMule\Connection
 */
class Database implements DatabaseInterface
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
     * @var \pdo
     */
    private $conn;

    /**
     * Database constructor.
     * @param $driver
     * @param $host
     * @param $database
     * @param $username
     * @param $password
     */
    public function __construct($driver,$host,$database,$username,$password)
    {
        $this->conn = new \pdo( $driver . ":host=".$host."; dbname=".$database."", $username, $password);
    }

    /**
     * Data base handler
     * @param $databaseConnectionKey
     * @return \PDO
     */
    public static function dbh($databaseConnectionKey = null)
    {
        if(!is_null($databaseConnectionKey)) {
            self::$active = $databaseConnectionKey;
        }

        if(!isset(self::$db[self::$active]) ||
            (isset(self::$db[self::$active]) && is_null(self::$db[self::$active]))){
            self::create(self::$active);
        }
        return self::$db[self::$active];
    }

    /**
     * Connection
     * @return \pdo
     */
    public function connection() : \pdo
    {
        return $this->conn;
    }

    /**
     * Driver
     */
    public function driver()
    {
        return $this->conn->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Change
     * @param $configName
     * @param $stick
     * @return $this
     */
    public function change($configName, $stick = false)
    {
        self::$active = $configName;
        self::$stick = $stick;
        return $this;
    }

    /**
     * Close
     * @param $configName
     * @return $this
     * @throws \Exception
     */
    public function close($configName)
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
     * Create
     * @param $config
     * @return \PDO
     * @throws \Exception
     */
    private function create($config)
    {
        try{
            //Fetch project connections
            $connections = [];
            if(method_exists("\\QueryMule\\Connections", "database")) {
                $connections = call_user_func_array( [ "\\QueryMule\\Connections", "database" ], [ ] );
            }

            //Does this config exist?
            if(array_key_exists($config, $connections)){
                self::$db[$config] = new self(
                    $connections[$config]['driver'],
                    $connections[$config]['host'],
                    $connections[$config]['database'],
                    $connections[$config]['username'],
                    $connections[$config]['password']
                );
            }else {
                throw new \Exception( $config . ' is not a valid DB connection' );
            }
        }catch(\PDOException $e){
            throw new \Exception( "Connection failed: " . $e->getMessage() );
        }
        return self::$db[$config];
    }
}