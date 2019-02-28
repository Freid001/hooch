<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Statement;


use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql;

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
     * @param FieldInterface $field
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereBetween(FieldInterface $field, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn(FieldInterface $field, array $values = []): FilterInterface;

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function orWhere(?FieldInterface $field, OperatorInterface $operator): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param $value
     * @return FilterInterface
     */
    public function orWhereLike(FieldInterface $field, $value): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereNotBetween(FieldInterface $field, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return SelectInterface|FilterInterface|QueryBuilderInterface
     */
    public function orWhereNotExists(Sql $subQuery): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereNotIn(FieldInterface $field, array $values = []): FilterInterface;

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function orWhereNot(?FieldInterface $field, OperatorInterface $operator): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param $value
     * @return FilterInterface
     */
    public function orWhereNotLike(FieldInterface $field, $value): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereBetween(FieldInterface $field, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn(FieldInterface $field, array $values = []): FilterInterface;

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function where(?FieldInterface $field, OperatorInterface $operator): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param $value
     * @return FilterInterface
     */
    public function whereLike(FieldInterface $field, $value): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereNotBetween(FieldInterface $field, $from, $to): FilterInterface;

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereNotExists(Sql $subQuery): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param array $values
     * @return FilterInterface
     */
    public function whereNotIn(FieldInterface $field, array $values = []): FilterInterface;

    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FilterInterface
     */
    public function whereNot(?FieldInterface $field, OperatorInterface $operator): FilterInterface;

    /**
     * @param FieldInterface $field
     * @param $value
     * @return FilterInterface
     */
    public function whereNotLike(FieldInterface $field, $value): FilterInterface;
}