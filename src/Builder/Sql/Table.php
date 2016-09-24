<?php namespace freidcreations\QueryMule\Builder\Sql;
use freidcreations\freidQuery\sql\row;

/**
 * @name table
 * @author Fraser Reid
 * @created 26/11/2015
 * @copyright Copyright (c) - 2016 Fraser Reid
 */
class Table
{
    /**
     * @var array
     */
    protected static $tables = [];

    /**
     * @var string
     */
    private $table;

    /**
     * @var string
     */
    private $databaseConnectionKey;

    /**
     * @var null
     */
    private $from = null;

    /**
     * Table constructor.
     * @param $databaseConnectionKey
     * @param $name
     */
    private function __construct($databaseConnectionKey,$name)
    {
        $this->databaseConnectionKey = $databaseConnectionKey;
        $this->table = $name;
    }

    /**
     * Table
     * @param $databaseConnectionKey
     * @param $name
     * @return mixed
     */
    public static function table($databaseConnectionKey,$name)
    {
        if (!isset(static::$tables[$databaseConnectionKey][$name])) {
            self::$tables[$databaseConnectionKey][$name] = new self($databaseConnectionKey,$name);
        }
        return self::$tables[$databaseConnectionKey][$name];
    }

    /**
     * Set
     * @param $key
     * @param $value
     */
    public function __set($key, $value = null)
    {
       $this->columns[$key] = $value;
    }

    /**
     * From
     * @param $sql
     * @return $this;
     */
    public function from($sql)
    {
        $this->from = $sql;
        return $this;
    }

    /**
     * Get
     * @param $key
     * @return null
     */
    public function __get($key)
    {
        if( array_key_exists( $key, $this->columns ) ) {
            return $this->columns[$key];
        }
        return null;
    }

    /**
     * Table Name
     * @return mixed
     */
    public function name()
    {
        return !is_null( $this->from ) ? '( ' . $this->from . ' ) AS ' . $this->table : $this->table;
    }

    /**
     * Database Connection Key
     */
    public function databaseConnectionKey()
    {
        return $this->databaseConnectionKey;
    }
}