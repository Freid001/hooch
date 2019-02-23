<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Sql\Mysql;


use Redstraw\Hooch\Query\Common\HasOperator;
use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Common\Sql\HasFullOuterJoin;
use Redstraw\Hooch\Query\Common\Sql\HasInnerJoin;
use Redstraw\Hooch\Query\Common\Sql\HasJoin;
use Redstraw\Hooch\Query\Common\Sql\HasLeftJoin;
use Redstraw\Hooch\Query\Common\Sql\HasRightJoin;
use Redstraw\Hooch\Query\Common\Sql\HasSet;
use Redstraw\Hooch\Query\Common\Sql\HasTable;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\UpdateInterface;

/**
 * Class Update
 * @package Redstraw\Hooch\Builder\Sql\Mysql
 */
class Update implements UpdateInterface
{
    use HasQuery;
    use HasOperator;
    use HasSet;
    use HasTable;
    use HasJoin;
    use HasLeftJoin;
    use HasRightJoin;
    use HasFullOuterJoin;
    use HasInnerJoin;

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var OnFilterInterface
     */
    private $onFilter;

    /**
     * Update constructor.
     * @param Query $query
     * @param Operator $operator
     */
    public function __construct(Query $query, Operator $operator)
    {
        $this->query = $query;
        $this->query->sql()->append(Sql::UPDATE);
        $this->query->appendSqlToClause(Sql::UPDATE);

        $this->operator = $operator;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::UPDATE,
        Sql::JOIN,
        Sql::SET,
        Sql::WHERE
    ]): Sql
    {
        if (in_array(Sql::WHERE, $clauses) && $this->filter) {
            $this->query->sql()->append($this->filter->build([Sql::WHERE]));
            $this->query->appendSqlToClause(Sql::WHERE);
        }

        if (in_array(Sql::JOIN, $clauses) && $this->onFilter) {
            $this->query->sql()->append($this->filter->build([Sql::JOIN]));
            $this->query->appendSqlToClause(Sql::JOIN);
        }

        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }

    /**
     * @param string $column
     * @param int $amount
     * @return UpdateInterface
     * @throws \Redstraw\Hooch\Query\Exception\SqlException
     */
    public function increment(string $column, int $amount): UpdateInterface
    {
        $this->set([$column=>$column."+".$amount]);

        return $this;
    }

    /**
     * @param string $column
     * @param int $amount
     * @return UpdateInterface
     * @throws \Redstraw\Hooch\Query\Exception\SqlException
     */
    public function decrement(string $column, int $amount): UpdateInterface
    {
        $this->set([$column=>$column."-".$amount]);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return UpdateInterface
     */
    public function filter(\Closure $callback): UpdateInterface
    {
        if(!empty($this->filter)){
            $callback->call($this->filter, $this->table, ...$this->joinTables);
        }

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return UpdateInterface
     */
    public function onFilter(\Closure $callback): UpdateInterface
    {
        if(!empty($this->onFilter)){
            $callback->call($this->onFilter, $this->table);
        }

        return $this;
    }

    /**
     * @param bool $ignore
     * @return UpdateInterface
     */
    public function ignoreAccent($ignore = true): UpdateInterface
    {
        $this->query->accent()->ignore($ignore);

        return $this;
    }

    /**
     * @param FilterInterface $filter
     */
    public function setFilter(FilterInterface $filter): void
    {
        $this->filter = $filter;
    }

    /**
     * @param OnFilterInterface $onFilter
     */
    public function setOnFilter(OnFilterInterface $onFilter): void
    {
        $this->onFilter = $onFilter;
    }
}