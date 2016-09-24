<?php namespace freidcreations\QueryMule\Query;
use freidcreations\QueryMule\Query\Table\AbstractTable;
use freidcreations\QueryMule\Builder\Sql\Common\TableCreate;
use freidcreations\QueryMule\Builder\Sql\Common\TableAlter;

/**
 * Interface QueryInterface
 * @package freidcreations\QueryMule\Query
 */
interface QueryInterface{

    /**
     * Query
     * @return AbstractTable
     */
    public static function query() : AbstractTable;

    /**
     * Generate create table statement
     * @param \Closure $columns
     * @return TableCreate
     */
    public function create(\Closure $columns) : TableCreate;

    /**
     * Generate alter table statement
     * @return TableAlter
     */
    public function alter() : TableAlter;

    /**
     * Select
     * @return select
     */
    public function select();

    /**
     * Insert
     * @return select
     */
    public function insert();

    /**
     * Update
     * @return update
     */
    public function update();

    /**
     * Delete
     * @return delete
     */
    public function delete();
}