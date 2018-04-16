<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Sql\Operator\Comparison;

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
     * @param $column
     * @param null|Comparison $operator
     * @param null $value
     * @param string $clause
     * @return FilterInterface
     */
    public function where($column, ?Comparison $operator = null, $value = null, $clause = self::WHERE);

    /**
     * @param $column
     * @param null|Comparison $operator
     * @param null $value
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $operator = null, $value = null);

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
     * @param $column
     * @param null|Comparison $operator
     * @param $value
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $operator = null, $value = null);

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}
