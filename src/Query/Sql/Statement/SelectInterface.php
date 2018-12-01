<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Operator\Comparison;

/**
 * Interface Select
 * @package QueryMule\Query\Sql\Statement
 */
interface SelectInterface extends FilterInterface
{
    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []): Sql;

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
     * @param array $table
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     * @throws SqlException
     */
    //public function join(array $table, $first, $operator = null, $second = null) : SelectInterface;

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
     * @param SelectInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(SelectInterface $select, bool $all = false);

    /**
     * @param $type
     * @param RepositoryInterface $table
     * @param $first
     * @param null|Comparison $comparison
     * @return mixed
     */
    public function join($type, RepositoryInterface $table, $first, ?Comparison $comparison = null);
}
