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
    const ALL = 'ALL';
    const AS = 'AS';
    const BY = 'BY';
    const COLS = 'COL';
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
    const OFFSET = 'OFFSET';
    const ORDER = 'ORDER BY';
    const SET = 'SET';
    const SELECT = 'SELECT';
    const SQL_STAR = '*';
    const TABLE = 'TABLE';
    const UNION = 'UNION';
    const UPDATE = 'UPDATE';
    const VALUES = 'VALUES';

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true) : SelectInterface;

    /**
     * @param array $cols
     * @param string|null $alias
     * @return SelectInterface
     */
    public function cols($cols = [self::SQL_STAR], $alias = null) : SelectInterface;

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table, $alias = null) : SelectInterface;

    /**
     * @param array $table
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     * @throws SqlException
     */
    public function leftJoin(array $table, $first, $operator = null, $second = null) : SelectInterface;

//    public function rightJoin() : SelectInterface;
//
//    public function crossJoin() : SelectInterface;
//
//    public function innerJoin() : SelectInterface;
//
//    public function outerJoin() : SelectInterface;


    /**
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return SelectInterface
     */
    public function on($first, $operator, $second) : SelectInterface;

    /**
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     */
    public function orOn($first, $operator = null, $second = null) : SelectInterface;

    /**
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @param string $clause
     * @return SelectInterface
     */
    public function where($column, $operator = null, $value = null, $clause = self::WHERE) : SelectInterface;

    /**
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @return SelectInterface
     */
    public function orWhere($column, $operator = null, $value = null) : SelectInterface;

    /**
     * @param string $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy($column, $alias = null) : SelectInterface;

    /**
     * @param string $column
     * @param string $sort
     * @param null $alias
     * @return SelectInterface
     */
    public function orderBy($column, $sort = 'desc', $alias = null) : SelectInterface;

    /**
     * @param int $limit
     * @return SelectInterface
     */
    public function limit($limit) : SelectInterface;

    /**
     * @param int $offset
     * @return SelectInterface
     */
    public function offset($offset) : SelectInterface;

    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(SelectInterface $select, $all = false) : SelectInterface;

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}
