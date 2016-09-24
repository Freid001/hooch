<?php namespace freidcreations\QueryMule\Builder\Console;
use freidcreations\QueryMule\Query\Console\AbstractConsole;

/**
 * Class console
 * @package freidcreations\freidQuery\bin
 */
class Console extends AbstractConsole
{
    const DATABASE = 'database';
    const MAKE = 'make';
    const MIGRATION = 'migration';
    const CONFIGURE = 'configure';
    const HELP = 'help';
    const VERSION = 'version';
    const LICENSE = 'license';

    /**
     * Run
     * @param $args
     * @return bool
     */
    public function run($args)
    {
        unset($args[0]);

        try {

            //Options
            $found = 0;
            foreach ( $args as $index => $arg ) {

                //Get sub arg
                $argArray = explode( '--', $arg );

                if ( isset( $argArray[ 1 ] ) ) {
                    switch ( $argArray[ 1 ] ) {
                        case self::HELP;
                            $this->help();
                            $found++;
                            break;
                        case self::LICENSE;
                            $this->license();
                            $found++;
                            break;
                        case self::VERSION;
                            $this->version();
                            $found++;
                            break;
                    }
                }
            }

            //Commands
            foreach ( $args as $index => $arg ) {

                //Get sub arg
                $argArray = explode( ':', $arg );

                //Fetch config path
                $path = json_decode($this->getResourceContents( $this->fetchResource( __DIR__ . '\\..\\Config\\Config.json', 'r' )));
                if ( !isset( $path->path ) &&
                    $argArray[0] != self::CONFIGURE
                ) {
                    $this->newLines( 1 );
                    $this->output( "Notice: QueryMule config files not found for this application, please run the install command.", self::BACKGROUND_YELLOW, 1 );
                    return false;
                }

                switch ( $argArray[ 0 ] ) {
                    case self::DATABASE;
                        $this->database($path->path)->run($argArray[1]);
                        $found++;
                        break;

                    case self::MIGRATION;
                        $this->migration($path->path)->run($argArray[1]);
                        $found++;
                        break;

                    case self::MAKE;
                        $this->make($path->path, $argArray[1]);
                        $found++;
                        break;

                    case self::CONFIGURE:
                        $this->configure();
                        $found++;
                        break;
                }
            }

            //Did we find any commands?
            if ( $found == 0 ) {
                $this->output( "No command found, try using --help.", self::BACKGROUND_RED, 1, true );
            }
        }catch(\Exception $e) {
            $this->output( "Something when wrong, recommend running: " . self::CONFIGURE . ".", self::BACKGROUND_RED, 1, true );
        }

        return false;
    }

    /**
     * Database
     * @param $configPath
     * @return Database
     */
    private function database($configPath) : Database
    {
        return new Database($configPath);
    }

    /**
     * Migration
     * @param $configPath
     * @return Migration
     */
    private function migration($configPath) : Migration
    {
        return new Database($configPath);
    }

    /**
     * Help
     */
    private function help()
    {
        $this->logo();

        //Options
        $this->output("Options:",self::YELLOW);
        $this->output("\t--help", self::GREEN, false);
        $this->output("\t\tDisplay the help message.");
        $this->output("\t--version", self::GREEN, false);
        $this->output("\tDisplay current version of queryMule.");
        $this->output("\t--license", self::GREEN, false);
        $this->output("\tDisplay package license.");

        //Commands
        $this->output("Configure:",self::YELLOW);
        $this->output("\tconfigure", self::GREEN, false);
        $this->output("\t\tConfigure QueryMule.");

        $this->output("Database:",self::YELLOW);

        $this->output("\tdatabase:add", self::GREEN, false);
        $this->output("\t\tAdd a new database connection.");

        $this->output("\tdatabase:connections", self::GREEN, false);
        $this->output("\tDisplay currently configured database connections.");

        $this->output("\tdatabase:remove", self::GREEN, false);
        $this->output("\t\tRemove an existing database configuration.");

        $this->output("Make:",self::YELLOW);

        $this->output("\tmake:migration", self::GREEN, false);
        $this->output("\t\tCreate a new migration class.");

        $this->output("\tmake:table", self::GREEN, false);
        $this->output("\t\tCreate a new table class.");

        $this->output("Migrate:",self::YELLOW);

        $this->output("\tmigrate:database", self::GREEN, false);
        $this->output("\tMigrate database version.");

        $this->output("\tmigrate:reset", self::GREEN, false);
        $this->output("\t\tReset your database.");

        $this->output("\tmigrate:rollback", self::GREEN, false);
        $this->output("\tRollback the latest migration.");
    }

    /**
     * License
     */
    private function license()
    {
        $resource = $this->fetchResource(__DIR__."\\..\\..\\..\\LICENSE","r");
        echo stream_get_contents($resource);
    }

    /**
     * Version
     */
    private function version()
    {
        $resource = $this->fetchResource(__DIR__."\\..\\..\\..\\composer.json","r");
        $content = (array)json_decode($this->getResourceContents($resource));

        if(isset($content['version'])){
            echo 'v' . $content['version'];
        }else {
            echo "unknown version number, please check composer.json file";
        }
    }

    /**
     * Configure
     */
    private function configure()
    {
        $this->output("Please specify a directory path with in your application for storing QueryMule files.\t",self::BACKGROUND_BLUE);
        $this->output("Leave blank to configure QueryMule at the root level.\t\t\t\t\t",self::BACKGROUND_BLUE);

        //Path
        $inputPath = $this->input("\\") . "\\";
        $realPath = dirname(__FILE__) . '\\..\\..\\..\\..\\..\\..\\' . $inputPath;

        //New line for spacing
        $this->newLines(1);

        //Does this path exist?
        if (is_dir($realPath)) {

            //Does QueryMule directory exist?
            $arrayPath = explode("\\",$inputPath);

            //Add QueryMule directory if it exists with in the realPath
            if(is_dir($realPath . "\\QueryMule")){
                $arrayPath[] = "QueryMule";
                $realPath .= "QueryMule";
            }

            //Fetch of create directory
            if(count($arrayPath)-1 >= 0 && $arrayPath[count($arrayPath)-1] == "QueryMule"){

                //Fetch existing config files
                $this->output('+ Existing QueryMule directory found at path.');
            }else {

                //Make queryMule directory
                $this->output('+ Creating QueryMule directory.',self::WHITE,2);
                mkdir($realPath . "\\QueryMule");

                //New real path
                $realPath .= "QueryMule";

                //Add new database connection
                $this->output("Configure database connection.\t\t\t\t\t\t",self::BACKGROUND_BLUE);

                if(!$this->database($realPath)->run('add')){
                    $this->output('Configuration failed, see errors.',self::BACKGROUND_RED,1,true);
                    return false;
                }
            }

            //Store config path for this application
            $this->writeResource(
                $this->fetchResource(__DIR__ . "..\\..\\Config\\Config.json", "w"),
                json_encode([ 'path' => $realPath ],JSON_PRETTY_PRINT)
            );

            //Composer namespace
            $composer = $this->fetchResource(__DIR__ . "\\..\\..\\..\\..\\..\\..\\composer.json", "r");
            if(!$composer){
                $this->output("Notice: Could not find project composer.json file, please ensure your project root contains a composer.json file.\t", self::BACKGROUND_YELLOW);
                $this->output("QueryMule has not configured correctly and may not run as expected, see warnings.\t\t\t\t\t", self::BACKGROUND_YELLOW);
                $this->output("Configuration completed, with warnings. \t\t\t\t\t\t\t\t\t\t",self::BACKGROUND_YELLOW);
                return false;
            }

            //Json decode composer
            $composer = json_decode($this->getResourceContents($composer), true);

            //Get composer.json autoload
            if(!isset($composer['autoload'])){
                $this->output("+ Could not find autoload in project composer.json file.");
                $this->output("+ Creating autoload in project composer.json file.");
                $composer['autoload'] = [];
            }

            //Add QueryMule PSR-4 namespace
            $composer['autoload']['psr-4']['QueryMule\\'] = ltrim(implode("/",$arrayPath), "/");
            $this->output("+ Writing QueryMule PSR-4 namespace to project composer.json file.");
            $this->writeResource(
                $this->fetchResource(__DIR__ . "\\..\\..\\..\\..\\..\\..\\composer.json", "w"),
                json_encode($composer,JSON_PRETTY_PRINT)
            );

            //Run composer dump-autoload to update project name spaces
            $this->output("+ Running composer dump-autoload.");
            exec('composer dump-autoload');

            //Configuration complete
            $this->newLines(1);
            $this->output('Configuration completed.',self::BACKGROUND_GREEN);
            return true;
        }else {
            $this->output("Directory not found for path: \\" . $inputPath, self::BACKGROUND_RED, 1, true );
        }

        return false;
    }

    /**
     * Make
     * @param $configPath
     * @param $type
     */
    private function make($configPath, $type)
    {
        switch($type){
            case 'migration':
                $this->output("Please specify a migration name.", self::BACKGROUND_BLUE);
                $name = $this->input(null);

                if(empty($name)){
                    $this->output("Migration name can not be blank.", self::BACKGROUND_RED, 1, true);
                    return false;
                }else{
                    $name = "Migration" . "_" . time() . "_" . $name;
                }

                $this->output("Please specify an existing database connection key, for this migration.", self::BACKGROUND_BLUE);
                $databaseKey = $this->input(null);

                //Fetch connections
                $connections = [];
                if(method_exists("\\QueryMule\\Connections", "database")) {
                    $connections = call_user_func_array( [ "\\QueryMule\\Connections", "database" ], [] );
                }

                //Does this key exist?
                $match = false;
                foreach($connections as $key => $connection){
                    if($key == $databaseKey){
                        $match = true;
                    }
                }

                if(!$match){
                    $this->output("The specified database connection key does not exist, try using database:connections to view a list of available databases.", self::BACKGROUND_RED,1,true);
                    return false;
                }

                //Make database directory if it does not already exist
                if(!is_dir($configPath . "\\" . $databaseKey)) {
                    mkdir($configPath . "\\" . $databaseKey);
                }

                //Make database migration directory if it does not already exist
                if(!is_dir($configPath . "\\" . $databaseKey . "\\Migration" )) {
                    mkdir($configPath . "\\" . $databaseKey . "\\Migration");
                }

                //Create new migration file
                $this->writeResource(
                    $this->fetchResource($configPath . "\\" . $databaseKey . "\\Migration\\" . $name . ".php", "w"),
                    make::migration($databaseKey,$name)
                );
                break;

            case 'table';
                $this->output("Please specify a class name.", self::BACKGROUND_BLUE);
                $name = $this->input(null);

                if(empty($name)){
                    $this->output("Class name can not be blank.", self::BACKGROUND_RED, 1, true);
                    return false;
                }

                $this->output("Please specify a table name.", self::BACKGROUND_BLUE);
                $table = $this->input(null);

                if(empty($table)){
                    $this->output("Table name can not be blank.", self::BACKGROUND_RED, 1, true);
                    return false;
                }

                $this->output("Please specify an existing database connection key, for this table.", self::BACKGROUND_BLUE);
                $databaseKey = $this->input(null);

                //Fetch connections
                $connections = [];
                if(method_exists("\\QueryMule\\Connections", "database")) {
                    $connections = call_user_func_array( [ "\\QueryMule\\Connections", "database" ], [] );
                }

                //Does this key exist?
                $match = false;
                foreach($connections as $key => $connection){
                    if($key == $databaseKey){
                        $match = true;
                    }
                }

                if(!$match){
                    $this->output("The specified database connection key does not exist, try using database:connections to view a list of available databases.", self::BACKGROUND_RED,1,true);
                    return false;
                }

                //Make database directory if it does not already exist
                if(!is_dir($configPath . "\\" . $databaseKey)) {
                    mkdir($configPath . "\\" . $databaseKey);
                }

                //Make database migration directory if it does not already exist
                if(!is_dir($configPath . "\\" . $databaseKey . "\\Table" )) {
                    mkdir($configPath . "\\" . $databaseKey . "\\Table");
                }

                //Create new migration file
                $this->writeResource(
                    $this->fetchResource($configPath . "\\" . $databaseKey . "\\Table\\" . $name . ".php", "w"),
                    make::table($databaseKey,$name,$table)
                );
                break;

            default:
                $this->output( "No command found, try using --help.", self::BACKGROUND_RED, 1, true );
        }

        return false;
    }

    //'freidcreations\\QueryMule\\' => array($vendorDir . '/freidcreations/QueryMule/src'),
}