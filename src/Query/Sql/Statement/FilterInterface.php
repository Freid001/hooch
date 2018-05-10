<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

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
     * @return Comparison
     */
    public function comparison();

    /**
     * @return Logical
     */
    public function logical();

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null);

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null);

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
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $comparison = null);

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []) : Sql;
}
