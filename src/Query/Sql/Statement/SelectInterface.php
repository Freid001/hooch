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
interface SelectInterface //extends FilterInterface
{
    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = []): Sql;

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true): SelectInterface;

    /**
     * @param array $cols
     * @param string|null $alias
     * @return SelectInterface
     */
    public function cols($cols = [Sql::SQL_STAR], $alias = null): SelectInterface;

    /**
     * @param RepositoryInterface $table
     * @param string|null $alias
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table, $alias = null): SelectInterface;

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
    public function groupBy($column, $alias = null): SelectInterface;

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
    public function limit(int $limit): SelectInterface;

    /**
     * @param int $offset
     * @return SelectInterface
     */
    public function offset(int $offset): SelectInterface;

    /**
     * @param string $first
     * @param string $operator
     * @param string $second
     * @return SelectInterface
     */
    public function on($first, $operator, $second): SelectInterface;

    /**
     * @param string $first
     * @param string|null $operator
     * @param string|null $second
     * @return SelectInterface
     */
    public function orOn($first, $operator = null, $second = null): SelectInterface;

    /**
     * @param $column
     * @return SelectInterface
     */
    public function asc($column): SelectInterface;

    /**
     * @param $column
     * @return SelectInterface
     */
    public function desc($column): SelectInterface;

    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(SelectInterface $select, bool $all = false): SelectInterface;
}
