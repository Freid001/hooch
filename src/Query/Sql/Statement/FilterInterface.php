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
    const IN = 'IN';
    const OR = 'OR';
    const NOT = 'NOT';
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
     * @param string $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column,array $values = []);

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column,array $values = []);

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}
