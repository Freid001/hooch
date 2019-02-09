<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql\Statement;


use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Interface Select
 * @package Redstraw\Hooch\Query\Sql\Statement
 */
interface SelectInterface extends QueryBuilderInterface, JoinInterface
{
    /**
     * @param array $cols
     * @param string|null $alias
     * @return SelectInterface|QueryBuilderInterface
     */
    public function cols(array $cols = [Sql::SQL_STAR], ?string $alias = null): SelectInterface;

    /**
     * @param \Closure $callback
     * @return SelectInterface
     */
    public function filter(\Closure $callback): SelectInterface;

    /**
     * @param FilterInterface $filter
     * @return void
     */
    public function setFilter(FilterInterface $filter): void;

    /**
     * @param \Closure $callback
     * @return SelectInterface
     */
    public function onFilter(\Closure $callback): SelectInterface;

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
     * @param string $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy(string $column, ?string $alias = null): SelectInterface;

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
     * @param string $column
     * @param string|null $order
     * @return SelectInterface
     */
    public function orderBy(string $column, ?string $order): SelectInterface;

    /**
     * @param QueryBuilderInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(QueryBuilderInterface $select, bool $all = false): SelectInterface;

    /**
     * @param string $column
     * @param OperatorInterface $operator
     * @return SelectInterface
     */
    public function having(string $column, OperatorInterface $operator): SelectInterface;
}
