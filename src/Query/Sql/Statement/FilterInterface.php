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
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []): Sql;

    /**
     * @return Comparison
     */
    public function comparison();

    /**
     * @param bool $ignore
     * @return FilterInterface
     */
    public function ignoreAccent($ignore = true);

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
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null);

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereBetween($column, $from, $to);

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery);

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column, array $values = []);

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function orWhereNot($column, ?Comparison $comparison = null, ?Logical $logical = null);

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereNotBetween($column, $from, $to);

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereNotExists(Sql $subQuery);

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereNotIn($column, array $values = []);

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null);

    /**
     * @param \Closure $column
     * @return FilterInterface
     */
    public function nestedWhere(\Closure $column);

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereBetween($column, $from, $to);

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery);

    /**
     * @param string $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column, array $values = []);

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $comparison = null, ?Logical $logical = null);

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereNotBetween($column, $from, $to);

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereNotExists(Sql $subQuery);

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereNotIn($column, array $values = []);
}
