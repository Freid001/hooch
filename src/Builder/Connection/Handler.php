<?php namespace freidcreations\QueryMule\Connection;
use freidcreations\QueryMule\Query\Connection\AbstractDatabase;

/**
 * @name Handler
 * @author handeler Reid
 * @created 26/11/2015
 * @copyright Copyright (c) - 2015 Fraser Reid
 */
class Handler extends AbstractDatabase
{
    /**
     * DB
     *
     * @return \PDO
     * @throws \Exception
     */
    public static function db()
    {
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

        return self::dbh();
    }
}