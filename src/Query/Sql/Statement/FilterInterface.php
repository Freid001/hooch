<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Query\Sql\Sql;

/**
 * Interface FilterInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface FilterInterface
{
    const WHERE = 'WHERE';
    const AND_WHERE = 'AND';
    const OR_WHERE = 'OR';

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
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}