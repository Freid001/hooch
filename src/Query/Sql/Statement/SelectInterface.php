<?php

namespace QueryMule\Query\Sql\Statement;


use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;

/**
 * Interface Select
 * @package QueryMule\Query\Sql\Statement
 */
interface SelectInterface
{
    /**
     * @param array $cols
     * @param string|null $alias
     * @return SelectInterface
     */
    public function cols(array $cols = [Sql::SQL_STAR], ?string $alias = null);

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
     * @param string|null $alias
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table, ?string $alias = null);

    /**
     * @param $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy($column, ?string $alias = null);

    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @return SelectInterface
     */
    public function join(string $type, RepositoryInterface $table, ?string $alias = null);

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function leftJoin(RepositoryInterface $table, ?string $alias, $column, ?OperatorInterface $operator = null);

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function rightJoin(RepositoryInterface $table, ?string $alias, $column, ?OperatorInterface $operator = null);

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function innerJoin(RepositoryInterface $table, ?string $alias, $column, ?OperatorInterface $operator = null);

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @param $column
     * @param OperatorInterface|null $operator
     * @return SelectInterface
     */
    public function fullOuterJoin(RepositoryInterface $table, ?string $alias, $column, ?OperatorInterface $operator = null);

    /**
     * @param int $limit
     * @return SelectInterface
     */
    public function limit(int $limit);

    /**
     * @param int $offset
     * @return SelectInterface
     */
    public function offset(int $offset);

    /**
     * @param $column
     * @param string|null $order
     * @return mixed
     */
    public function orderBy($column, ?string $order);

    /**
     * @param QueryBuilderInterface $select
     * @param bool $all
     * @return mixed
     */
    public function union(QueryBuilderInterface $select, bool $all = false);
}
