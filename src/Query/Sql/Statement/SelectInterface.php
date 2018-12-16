<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Operator\Comparison;

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
    public function cols($cols = [Sql::SQL_STAR], $alias = null);

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table, $alias = null);

//    public function rightJoin() : SelectInterface;
//
//    public function crossJoin() : SelectInterface;
//
//    public function innerJoin() : SelectInterface;
//
//    public function outerJoin() : SelectInterface;

    /**
     * @param string $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy($column, $alias = null);

    /**
     * @param string $type
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @param $column
     * @return mixed
     */
    public function join(string $type, RepositoryInterface $table, ?string $alias, $column);

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
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return SelectInterface
     */
    //public function on($first, $operator, $second): SelectInterface;

    /**
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     */
    //public function orOn($first, $operator = null, $second = null): SelectInterface;

    /**
     * @param $column
     * @param $order
     * @return SelectInterface
     */
    public function orderBy($column, $order);

    /**
     * @param QueryBuilderInterface $select
     * @param bool $all
     * @return mixed
     */
    public function union(QueryBuilderInterface $select, bool $all = false);
}
