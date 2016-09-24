<?php namespace QueryMule; 

/** 
 * Class Connections 
 * @package QueryMule 
 */ 
class Connections
{
   /** 
    * Database 
    * @return array 
    */ 
    public static function database()
    {
        return [
            'Library' => [
                'driver'    => 'some_driver',
                'host'      => 'some_host',
                'database'  => 'some_database',
                'username'  => 'some_username',
                'password'  => 'some_password'
            ]
        ];
    }
}
