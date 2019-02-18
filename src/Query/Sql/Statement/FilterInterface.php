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
     * @param string|null $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereBetween(?string $column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery): FilterInterface;

    /**
     * @param string|null $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn(?string $column, array $values = []): FilterInterface;

    /**
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function orWhere(?string $column, OperatorInterface $operator): FilterInterface;

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereLike(?string $column, $value): FilterInterface;

    /**
     * @param string|null $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereNotBetween(?string $column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return SelectInterface|FilterInterface|QueryBuilderInterface
     */
    public function orWhereNotExists(Sql $subQuery): FilterInterface;

    /**
     * @param string|null $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereNotIn(?string $column, array $values = []): FilterInterface;

    /**
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function orWhereNot(?string $column, OperatorInterface $operator): FilterInterface;

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereNotLike(?string $column, $value): FilterInterface;

    /**
     * @param string|null $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereBetween(?string $column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery): FilterInterface;

    /**
     * @param string|null $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn(?string $column, array $values = []): FilterInterface;

    /**
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function where(?string $column, OperatorInterface $operator): FilterInterface;

    /**
     * @param string|null $column
     * @param $value
     * @return FilterInterface
     */
    public function whereLike(?string $column, $value): FilterInterface;

    /**
     * @param string|null $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereNotBetween(?string $column, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereNotExists(Sql $subQuery): FilterInterface;

    /**
     * @param string|null $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereNotIn(?string $column, array $values = []): FilterInterface;

    /**
     * @param string|null $column
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function whereNot(?string $column, OperatorInterface $operator): FilterInterface;

    /**
     * @param string|null $column
     * @param $value
     * @return FilterInterface
     */
    public function whereNotLike(?string $column, $value): FilterInterface;
}