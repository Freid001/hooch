<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Interface Select
 * @package QueryMule\Query\Sql\Statement
 */
interface SelectInterface extends FilterInterface
{
    const COLS = 'COL';
    const COL_AS = 'AS';
    const COUNT = 'COUNT';
    const DELETE = 'DELETE';
    const DISTINCT = 'DISTINCT';
    const FETCH = 'fetch';
    const FETCH_ALL = 'fetchAll';
    const FETCH_COLUMN = 'fetchColumn';
    const FETCH_COLUMN_COUNT = 'columnCount';
    const FETCH_COLUMN_META = 'getColumnMeta';
    const FETCH_ROW_COUNT = 'rowCount';
    const FROM = 'FROM';
    const GROUP = 'GROUP BY';
    const HAVING = 'HAVING';
    const IN = 'IN';
    const INSERT = 'INSERT';
    const INTO = 'INTO';
    const LIMIT = 'LIMIT';
    const ORDER = 'ORDER BY';
    const SET = 'SET';
    const SELECT = 'SELECT';
    const SQL_STAR = '*';
    const TABLE = 'TABLE';
    const UPDATE = 'UPDATE';
    const VALUES = 'VALUES';

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true) : SelectInterface;

    /**
     * @param array $cols
     * @param null $alias
     * @return SelectInterface
     */
    public function cols($cols = [self::SQL_STAR], $alias = null) : SelectInterface;

    /**
     * @param RepositoryInterface $table
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table, $alias = null) : SelectInterface;

    /**
     * @param array $table
     * @param null $first
     * @param null $operator
     * @param null $second
     * @return SelectInterface
     * @throws SqlException
     */
    public function leftJoin(array $table, $first = null, $operator = null, $second = null) : SelectInterface;

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $clause
     * @return SelectInterface
     */
    public function where($column, $operator = null, $value = null, $clause = self::WHERE) : SelectInterface;

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @return SelectInterface
     */
    public function orWhere($column, $operator = null, $value = null) : SelectInterface;

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}