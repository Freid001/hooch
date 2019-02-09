<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql\Statement;


use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Interface FilterInterface
 * @package Redstraw\Hooch\Query\Sql\Statement
 */
interface FilterInterface extends QueryBuilderInterface
{
    /**
     * @param \Closure $callback
     * @return FilterInterface
     */
    public function nestedWhere(\Closure $callback): FilterInterface;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereBetween($column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery): FilterInterface;

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column, array $values = []): FilterInterface;

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function orWhere($column, OperatorInterface $operator): FilterInterface;

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereLike($column, $value): FilterInterface;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereNotBetween($column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return SelectInterface|FilterInterface|QueryBuilderInterface
     */
    public function orWhereNotExists(Sql $subQuery): FilterInterface;

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereNotIn($column, array $values = []): FilterInterface;

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function orWhereNot($column, OperatorInterface $operator): FilterInterface;

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereNotLike($column, $value): FilterInterface;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereBetween($column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery): FilterInterface;

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column, array $values = []): FilterInterface;

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function where($column, OperatorInterface $operator): FilterInterface;

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereLike($column, $value): FilterInterface;

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereNotBetween($column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return SelectInterface|FilterInterface|QueryBuilderInterface
     */
    public function whereNotExists(Sql $subQuery): FilterInterface;

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereNotIn($column, array $values = []): FilterInterface;

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function whereNot($column, OperatorInterface $operator): FilterInterface;

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereNotLike($column, $value): FilterInterface;
}