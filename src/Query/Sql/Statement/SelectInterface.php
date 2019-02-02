<?php

declare(strict_types=1);

namespace QueryMule\Query\Sql\Statement;


use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Interface Select
 * @package QueryMule\Query\Sql\Statement
 */
interface SelectInterface extends QueryBuilderInterface
{
    /**
     * @param array $cols
     * @param string|null $alias
     * @return SelectInterface|QueryBuilderInterface
     */
    public function cols(array $cols = [Sql::SQL_STAR], ?string $alias = null): SelectInterface;

    /**
     * @return FilterInterface|null
     */
    public function filter(): ?FilterInterface;

    /**
     * @param FilterInterface $filter
     * @return void
     */
    public function setFilter(FilterInterface $filter): void;

    /**
     * @return OnFilterInterface|null
     */
    public function onFilter(): ?OnFilterInterface;

    /**
     * @param OnFilterInterface $onFilter
     * @return void
     */
    public function setOnFilter(OnFilterInterface $onFilter): void;

    /**
     * @param RepositoryInterface $table
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table): SelectInterface;

    /**
     * @param $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy($column, ?string $alias = null): SelectInterface;

    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @return SelectInterface
     */
    public function join(string $type, RepositoryInterface $table): SelectInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function leftJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): SelectInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function rightJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): SelectInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function innerJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): SelectInterface;

    /**
     * @param RepositoryInterface $table
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function fullOuterJoin(RepositoryInterface $table, $column, ?OperatorInterface $operator = null): SelectInterface;

    /**
     * @param int $limit
     * @return SelectInterface
     */
    public function limit(int $limit): SelectInterface;

    /**
     * @param int $offset
     * @return SelectInterface
     */
    public function offset(int $offset): SelectInterface;

    /**
     * @param $column
     * @param string|null $order
     * @return SelectInterface
     */
    public function orderBy($column, ?string $order): SelectInterface;

    /**
     * @param QueryBuilderInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(QueryBuilderInterface $select, bool $all = false): SelectInterface;

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return SelectInterface
     */
    public function having($column, OperatorInterface $operator): SelectInterface;
}
