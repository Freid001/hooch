<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Sql\Mysql;


use Redstraw\Hooch\Query\Common\HasOperator;
use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Common\Sql\HasCols;
use Redstraw\Hooch\Query\Common\Sql\HasFrom;
use Redstraw\Hooch\Query\Common\Sql\HasFullOuterJoin;
use Redstraw\Hooch\Query\Common\Sql\HasGroupBy;
use Redstraw\Hooch\Query\Common\Sql\HasHaving;
use Redstraw\Hooch\Query\Common\Sql\HasInnerJoin;
use Redstraw\Hooch\Query\Common\Sql\HasJoin;
use Redstraw\Hooch\Query\Common\Sql\HasLeftJoin;
use Redstraw\Hooch\Query\Common\Sql\HasLimit;
use Redstraw\Hooch\Query\Common\Sql\HasOffset;
use Redstraw\Hooch\Query\Common\Sql\HasOrderBy;
use Redstraw\Hooch\Query\Common\Sql\HasRightJoin;
use Redstraw\Hooch\Query\Common\Sql\HasUnion;
use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Class Select
 * @package Redstraw\Hooch\Builder\Sql\Sqlite
 */
class Select implements SelectInterface
{
    use HasQuery;
    use HasOperator;
    use HasCols;
    use HasFrom;
    use HasGroupBy;
    use HasLimit;
    use HasOffset;
    use HasUnion;
    use HasOrderBy;
    use HasJoin;
    use HasLeftJoin;
    use HasRightJoin;
    use HasInnerJoin;
    use HasFullOuterJoin;
    use HasHaving;

    /**
     * @var FilterInterface|QueryBuilderInterface
     */
    private $filter;

    /**
     * @var OnFilterInterface|QueryBuilderInterface
     */
    private $onFilter;

    /**
     * Select constructor.
     * @param Query $query
     * @param Operator $operator
     */
    public function __construct(Query $query, Operator $operator)
    {
        $this->query = $query;
        $this->operator = $operator;
        $this->query->sql()->append(Sql::SELECT);
        $this->query->toClause(Sql::SELECT);
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::SELECT,
        Sql::COLS,
        Sql::FROM,
        Sql::JOIN,
        Sql::WHERE,
        Sql::GROUP,
        Sql::ORDER,
        Sql::HAVING,
        Sql::LIMIT,
        Sql::OFFSET,
        Sql::UNION
    ]): Sql
    {
        if (in_array(Sql::WHERE, $clauses) && $this->filter) {
            $this->query->sql()->append($this->filter->build([Sql::WHERE]));
            $this->query->toClause(Sql::WHERE);
        }

        if (in_array(Sql::JOIN, $clauses) && $this->onFilter) {
            $this->query->sql()->append($this->onFilter->build([Sql::JOIN]));
            $this->query->toClause(Sql::JOIN);
        }

        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        $this->columnIndex = 0;
        $this->columnKeys = [];

        return $sql;
    }

    /**
     * @param \Closure $callback
     * @return SelectInterface
     */
    public function filter(\Closure $callback): SelectInterface
    {
        if(!empty($this->filter)){
            $callback->call($this->filter, $this->table, ...$this->joinTables);
        }

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return SelectInterface
     */
    public function onFilter(\Closure $callback): SelectInterface
    {
        if(!empty($this->onFilter)){
            $callback->call($this->onFilter, $this->table);
        }

        return $this;
    }

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true): SelectInterface
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
