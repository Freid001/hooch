<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Query\Sql\Sql;
use QueryMule\Sql\Operator\Comparison;
use QueryMule\Sql\Operator\Logical;

/**
 * Interface FilterInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface FilterInterface
{
    /**
     * @param bool $ignore
     * @return FilterInterface
     */
    public function ignoreAccent($ignore = true);

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null $value
     * @param null|Logical $logical
     * @return mixed
     */
    public function where($column, ?Comparison $comparison = null, $value = null, ?Logical $logical = null);

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null $value
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, $value = null);

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
     * @param null|Comparison $comparison
     * @param $value
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $comparison = null, $value = null);

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}
