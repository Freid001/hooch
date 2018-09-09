<?php

namespace QueryMule\Builder\Sql\Generic;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasColumnClause;
use QueryMule\Query\Sql\Clause\HasFromClause;
use QueryMule\Query\Sql\Clause\HasGroupByClause;
use QueryMule\Query\Sql\Clause\HasJoinClause;
use QueryMule\Query\Sql\Clause\HasLimitClause;
use QueryMule\Query\Sql\Clause\HasOffsetClause;
use QueryMule\Query\Sql\Clause\HasOrderByClause;
use QueryMule\Query\Sql\Clause\HasUnionClause;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Common\HasWhere;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Sql\Operator\Comparison;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Sqlite
 */
abstract class Select
{
//    /**
//     * @param $column
//     * @return SelectInterface
//     */
//    public function asc($column): SelectInterface
//    {
//        $sql = $this->orderByClause(
//            $this->addAccent($column, '.'),
//            SQL::ASC,
//            !empty($this->queryGet(Sql::ORDER))
//        );
//
//        $this->queryAdd(Sql::ORDER, $sql);
//
//        return $this;
//    }
//
//
//    /**
//     * @param string $first
//     * @param string $operator
//     * @param string $second
//     * @return SelectInterface
//     */
//    public function on($first, $operator, $second): SelectInterface
//    {
//        $this->queryAdd(Sql::JOIN, $this->onClause($first, $operator, $second, Sql::ON));
//
//        return $this;
//    }
//
//    /**
//     * @param string $first
//     * @param null $operator
//     * @param null $second
//     * @return SelectInterface
//     */
//    public function orOn($first, $operator = null, $second = null): SelectInterface
//    {
//        $this->queryAdd(Sql::JOIN, $this->onClause($first, $operator, $second, Sql:: OR));
//
//        return $this;
//    }
//    public function having()
//    {
//
//    }
//
//    /**
//     * @param RepositoryInterface $table
//     * @param null|string $alias
//     * @param null|string $column
//     * @param null|Comparison $comparison
//     * @return SelectInterface
//     * @throws SqlException
//     */
//    public function join(RepositoryInterface $table, ?string $alias, $column, ?Comparison $comparison): SelectInterface
//    {
//
//        $this->queryAdd(Sql::JOIN, $this->joinClause(Sql::JOIN_LEFT, $table, $alias));
//
//
////        if ($table instanceof RepositoryInterface) {
////            $this->queryAdd(Sql::JOIN, $this->joinClause(Sql::JOIN_LET, $table, $alias));
////            return $this->on($first, $operator, $second);
////        } else {
////            throw new SqlException('Table must be instance of RepositoryInterface');
////        }
//    }
}
