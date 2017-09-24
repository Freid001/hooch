<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Table\TableInterface;

/**
 * Interface Select
 * @package QueryMule\Query\Sql\Statement
 */
interface SelectInterface
{
    const AND_WHERE = 'AND';
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
    const INNER_JOIN = 'INNER JOIN';
    const INSERT = 'INSERT';
    const INTO = 'INTO';
    const JOIN = 'JOIN';
    const LEFT_JOIN = 'LEFT JOIN';
    const LEFT_OUTER_JOIN = 'LEFT OUTER JOIN';
    const LIMIT = 'LIMIT';
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
     * @param array $cols
     * @param null $alias
     * @return mixed
     */
    public function cols($cols = [self::SQL_STAR], $alias = null) : SelectInterface;

    /**
     * @param TableInterface $table
     * @return mixed
     */
    public function from(TableInterface $table, $alias = null) : SelectInterface;

    /**
     * @return Sql
     */
    public function build() : Sql;
}