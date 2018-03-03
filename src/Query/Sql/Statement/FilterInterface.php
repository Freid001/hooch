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
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @param string $clause
     * @return FilterInterface
     */
    public function where($column, $operator = null, $value = null, $clause = self::WHERE);

     /**
      * @param string $column
      * @param string|null $operator
      * @param string|null $value
      * @return FilterInterface
      */
    public function orWhere($column, $operator = null, $value = null);

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}
