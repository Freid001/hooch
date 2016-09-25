<?php namespace freidcreations\QueryMule\Query\Table;
use freidcreations\QueryMule\Builder\Sql\Common\TableAlter;
use freidcreations\QueryMule\Builder\Sql\Common\TableCreate;
use freidcreations\QueryMule\Query\Connection\AbstractDatabase;
use freidcreations\QueryMule\Query\QueryInterface;
use freidcreations\QueryMule\Builder\Sql\Table;

use freidcreations\freidQuery\sql\statement\statement;
use freidcreations\freidQuery\sql\statement\select;
use freidcreations\freidQuery\sql\statement\insert;
use freidcreations\freidQuery\sql\statement\update;
use freidcreations\freidQuery\sql\statement\delete;

/**
 * @name AbstractTable
 * @author Fraser Reid
 * @created 06/03/2016
 * @copyright Copyright (c) - 2016 Fraser Reid
 * @since v1.0.0
 */
abstract class AbstractTable implements QueryInterface
{
    /**
     * @var array
     */
    private $property = [
        'statement' => null,
        'table'     => null
    ];

    /**
     * AbstractTable constructor.
     * @param $databaseConnectionKey
     * @param $name
     */
    public function __construct($databaseConnectionKey,$name){
        $this->property['table'] = new Table($databaseConnectionKey,$name);
    }

//    /**
//     * Table
//     * @param $databaseConnectionKey
//     * @param $name
//     * @return table|self
//     */
//    protected static function table($databaseConnectionKey,$name){
//        if (!isset(static::$tables[$databaseConnectionKey][$name])) {
//            self::$tables[$databaseConnectionKey][$name] = new static($databaseConnectionKey,$name);
//        }
//        return self::$tables[$databaseConnectionKey][$name];
//    }

    /**
     * @param $key
     * @return table|statement|select|insert|update|delete|null
     */
//    public function __get( $key ){
//        if( array_key_exists( $key, $this->property ) ){
//            return $this->property[ $key ];
//        }
//        return null;
//    }

    /**
     * Inherit a statement
     * @param query $query
     * @return statement
     */
//    final public function inherit( query $query ){
//        return $this->property['statement'] = $query->statement;
//    }

    /**
     * @param \Closure $columns
     * @return TableCreate
     * @throws \Exception
     */
    final public function create(\Closure $columns) : TableCreate
    {
        return $this->property['statement'] = TableCreate::make($this->property['table'])->reset()->create($columns);
    }

    /**
     * @return TableAlter
     * @throws \Exception
     */
    final public function alter() : TableAlter
    {
        return $this->property['statement'] = TableAlter::make($this->property['table'])->reset()->alter();
    }






//    /**
//     * Generate create table statement
//     * @param \Closure $columns
//     * @return $this|\PDO
//     */
//    final public function alter(\Closure $columns){
//        return $this->property['statement'] = TableCreate::instance($this->table)->reset()->alter($columns);
//    }
//
//    /**
//     * Generate create table statement
//     * @param \Closure $columns
//     * @return $this|\PDO
//     */
//    final public function drop(\Closure $columns){
//        return $this->property['statement'] = TableCreate::instance($this->table)->reset()->drop($columns);
//    }


    /**
     * Generate select statement
     * @param $distinct
     * @return select
     */
    final public function select( $distinct = false ){
        return $this->property['statement'] = select::instance($this->table)->reset()->select($distinct);
    }

    /**
     * Generate insert statement
     * @return insert
     */
    final public function insert(){
        return $this->property['statement'] = insert::instance( $this->table )->reset()->insert();
    }

    /**
     * Generate update statement
     * @return update
     */
    final public function update(){
        return $this->property['statement'] = update::instance( $this->table )->reset();
    }

    /**
     * Generate delete statement
     * @return delete
     */
    final public function delete(){
        return $this->property['statement'] = delete::instance( $this->table )->reset();
    }
}
