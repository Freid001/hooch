<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Sql\Sql;

/**
 * Interface FilterInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface FilterInterface
{
    const WHERE = 'WHERE';
    const AND = 'AND';
    const OR = 'OR';
    const ON = 'ON';
    const JOIN = 'JOIN';
    const LEFT_JOIN = 'LEFT JOIN';

    /**
     * @param bool $ignore
     * @return FilterInterface
     */
    public function ignoreAccent($ignore = true);

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @param string $clause
     * @return FilterInterface
     */
    public function where($column, $operator = null, $value = null, $clause = self::WHERE);

     /**
      * @param $column
      * @param null $operator
      * @param null $value
      * @return FilterInterface
      */
    public function orWhere($column, $operator = null, $value = null);

    /**
     * @param array $table
     * @param null $first
     * @param null $operator
     * @param null $second
     * @return SelectInterface
     * @throws SqlException
     */
    public function leftJoin(array $table, $first = null, $operator = null, $second = null);

    /**
     * @param $first
     * @param null $operator
     * @param null $second
     * @return FilterInterface
     */
    public function on($first, $operator, $second);

    /**
     * @param $first
     * @param null $operator
     * @param null $second
     * @return FilterInterface
     */
    public function orOn($first, $operator, $second);

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}