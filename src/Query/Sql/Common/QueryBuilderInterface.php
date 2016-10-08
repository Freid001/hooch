<?php namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Builder\Sql\Sql;

/**
 * Interface QueryBuilderInterface
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
interface QueryBuilderInterface
{
    const ADD = 'ADD';
    const ALTER_TABLE = 'ALTER TABLE';
    const AND_WHERE = 'AND';
    const CREATE_TABLE = 'CREATE TABLE';
    const CREATE_TEMPORARY_TABLE  = 'CREATE TEMPORARY TABLE';
    const COLS = 'COL';
    const COL_AS = 'AS';
    const CONSTRAINT = 'CONSTRAINT';
    const COUNT = 'COUNT';
    const DELETE = 'DELETE';
    const DISTINCT = 'DISTINCT';
    const DROP = 'DROP';
    const FETCH = 'fetch';
    const FETCH_ALL = 'fetchAll';
    const FETCH_COLUMN = 'fetchColumn';
    const FETCH_COLUMN_COUNT = 'columnCount';
    const FETCH_COLUMN_META = 'getColumnMeta';
    const FETCH_ROW_COUNT = 'rowCount';
    const FROM = 'FROM';
    const GROUP = 'GROUP BY';
    const HAVING = 'HAVING';
    const IF_NOT_EXISTS = 'IF NOT EXISTS';
    const IN = 'IN';
    const INNER_JOIN = 'INNER JOIN';
    const INSERT = 'INSERT';
    const INTO = 'INTO';
    const JOIN = 'JOIN';
    const LEFT_JOIN = 'LEFT JOIN';
    const LEFT_OUTER_JOIN = 'LEFT OUTER JOIN';
    const LIMIT = 'LIMIT';
    const MODIFY = 'MODIFY';
    const ON = 'ON';
    const OR_WHERE = 'OR';
    const ORDER = 'ORDER BY';
    const RIGHT_JOIN = 'RIGHT JOIN';
    const RIGHT_OUTER_JOIN = 'RIGHT OUTER JOIN';
    const SET = 'SET';
    const SELECT = 'SELECT';
    const SQL_STAR = '*';
    const TABLE = 'TABLE';
    const UPDATE = 'UPDATE';
    const VALUES = 'VALUES';
    const WHERE = 'WHERE';

    /**
     * TableCreate constructor.
     * @param Table $table
     */
    public function __construct(Table $table);

    /**
     * Make
     * @param Table $table
     * @return self
     */
    public static function make(Table $table);

    /**
     * Table
     * @return Table
     */
    public function table() : Table;

    /**
     * Execute
     * @return \PDOStatement
     * @throws \Exception
     */
    public function execute();

    /**
     * Build
     * @return Sql
     */
    public function build() : Sql;

    /**
     * Reset SQL
     * @return $this
     */
    public function reset();

    /**
     * Accent
     * @return string
     */
    public function accent($string, $tableName = false);
}