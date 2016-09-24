<?php namespace freidcreations\QueryMule\Builder\Console;
use freidcreations\QueryMule\Query\Console\AbstractConsole;

/**
 * Class Database
 * @package freidcreations\QueryMule\src\Builder\Console
 */
class Database extends AbstractConsole
{
    const CONNECTIONS = 'connections';
    const ADD = 'add';
    const REMOVE = 'remove';

    /**
     * @var string
     */
    private $configPath;

    /**
     * Database constructor.
     * @param $path
     * @param $command
     */
    public function __construct($path)
    {
        $this->configPath = $path;
    }

    /**
     * Run
     * @param $configPath
     * @param $command
     */
    public function run($command)
    {
        //Run command
        switch($command){
            case self::CONNECTIONS:
                $this->connections();
                break;

            case self::ADD:
                $this->add();
                break;

            case self::REMOVE:
                $this->remove();
                break;

            default:
                $this->output( "No command found, try using --help.", self::BACKGROUND_RED, 1, true );
        }
    }

    /**
     * Add
     * @return bool
     */
    private function add()
    {
        $connection = [];

        $this->output("Please specify a database connection key.",self::BACKGROUND_BLUE);
        $key = $this->input(null);

        //Driver
        $this->output("Driver (pgsql or mysql)",self::BACKGROUND_BLUE);
        $driver = $this->input(null);

        if(in_array($driver,['pgsql','mysql'])) {
            $connection['driver'] = $driver;
        }else {
            $this->output("Driver not found, please use a currently supported driver: pgsql or mysql.",self::BACKGROUND_RED,3,true);
            return false;
        }

        //Host
        $this->output("Host",self::BACKGROUND_BLUE);
        $connection['host'] = $this->input(null);

        //Database
        $this->output("Database",self::BACKGROUND_BLUE);
        $connection['database'] = $this->input(null);

        //Username
        $this->output("Username",self::BACKGROUND_BLUE);
        $connection['username'] = $this->input(null);

        //Password
        $this->output("Password",self::BACKGROUND_BLUE);
        $connection['password'] = $this->input(null,true);

        //Fetch old connections
        $connections = [];
        if(method_exists("\\QueryMule\\Connections", "database")) {
            $connections = call_user_func_array( [ "\\QueryMule\\Connections", "database" ], [ ] );
        }

        //Add new connection
        $connections[$key] = $connection;

        //Convert array to template string
        $connectionsString = '';
        $connectionKeys = array_keys($connections);
        foreach($connections as $key => $connection) {
            if(!empty($connectionsString)){
                $connectionsString .= ",\n";
            }

            $connectionsString .= "            '" . $key . "' => [\n" .
                "                'driver'   => '" . $connection['driver'] . "',\n" .
                "                'host'     => '" . $connection['host'] . "',\n" .
                "                'database' => '" . $connection['database'] . "',\n" .
                "                'username' => '" . $connection['username'] . "',\n" .
                "                'password' => '" . $connection['password'] . "'\n" .
                "            ]";

            if($key == end($connectionKeys)){
                $connectionsString .= "\n";
            }
        }

        //Write new connections.php
        $resource = $this->fetchResource( $this->configPath . "\\Connections.php", "w" );

        //Store config path for this application
        $this->writeResource(
            $resource,
            Make::connections($connectionsString)
        );

        //Connection added
        $this->newLines(1);
        $this->output('Database connection added.',self::BACKGROUND_GREEN);

        return true;
    }

    /**
     * Connections
     * @return bool
     */
    private function connections()
    {
        //Output connections
        if(method_exists("\\QueryMule\\Connections", "database")) {
            $connections = call_user_func_array(["\\QueryMule\\Connections", "database"],[]);

            if(empty($connections)){
                $this->output('Notice: No connections found, please run database:add', self::BACKGROUND_YELLOW);
            }

            foreach($connections as $key => $connection) {

                //Connection Key
                $this->output($key.':',self::YELLOW);

                //Driver
                if ( !empty( $connection['driver'] ) ) {
                    $this->output( "\tdriver:" . $connection['driver'], self::GREEN );
                } else {
                    $this->output( "\t", self::WHITE, 0 );
                    $this->output( "Driver missing", self::BACKGROUND_RED );
                }

                //Host
                if ( !empty( $connection['host'] ) ) {
                    $this->output( "\thost: " . $connection['host'], self::GREEN );
                } else {
                    $this->output( "\t", self::WHITE, 0 );
                    $this->output( "Host missing", self::BACKGROUND_RED );
                }

                //Username
                if ( !empty( $connection['username'] ) ) {
                    $this->output( "\tusername: " . $connection['username'], self::GREEN );
                } else {
                    $this->output( "\t", self::WHITE, 0 );
                    $this->output( "Username missing", self::BACKGROUND_RED );
                }

                //Password
                if ( !empty( $connection['password'] ) ) {
                    $this->output( "\tpassword: ******", self::GREEN );
                } else {
                    $this->output( "\t", self::WHITE, 0 );
                    $this->output( "Password missing", self::BACKGROUND_RED );
                }
            }

            return true;
        }else {
            $this->output('Notice: No connections file, please run database:add', self::BACKGROUND_YELLOW);
        }

        return false;
    }

    /**
     * Remove
     */
    private function remove()
    {
        $this->output("Please specify a database connection key which you want to remove.",self::BACKGROUND_BLUE);
        $key = $this->input(null);

        //Fetch connections
        $connections = [];
        if(method_exists("\\QueryMule\\Connections", "database")) {
            $connections = call_user_func_array( [ "\\QueryMule\\Connections", "database" ], [ ] );
        }

        //Remove connection key
        unset($connections[$key]);

        //Convert array to template string
        $connectionsString = '';
        $connectionKeys = array_keys($connections);
        foreach($connections as $key => $connection) {
            if(!empty($connectionsString)){
                $connectionsString .= ",\n";
            }

            $connectionsString .= "            '" . $key . "' => [\n" .
                "                'driver'   => '" . $connection['driver'] . "',\n" .
                "                'host'     => '" . $connection['host'] . "',\n" .
                "                'database' => '" . $connection['database'] . "',\n" .
                "                'username' => '" . $connection['username'] . "',\n" .
                "                'password' => '" . $connection['password'] . "'\n" .
                "            ]";

            if($key == end($connectionKeys)){
                $connectionsString .= "\n";
            }
        }

        //Write new connections.json
        $resource = $this->fetchResource( $this->configPath . "\\Connections.php", "w" );

        //Store config path for this application
        $this->writeResource(
            $resource,
            Make::connections($connectionsString)
        );

        //Connection added
        $this->newLines(1);
        $this->output('Database connection removed.',self::BACKGROUND_GREEN);

        return true;
    }
}