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
                'driver'    => 'mysql',
                'host'      => '127.0.0.1',
                'database'  => 'Library',
                'username'  => 'root',
                'password'  => ''
            ]
        ];
    }
}
