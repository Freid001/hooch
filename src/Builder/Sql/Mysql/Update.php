<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Sql\Mysql;


use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Common\Sql\HasFullOuterJoin;
use Redstraw\Hooch\Query\Common\Sql\HasInnerJoin;
use Redstraw\Hooch\Query\Common\Sql\HasJoin;
use Redstraw\Hooch\Query\Common\Sql\HasLeftJoin;
use Redstraw\Hooch\Query\Common\Sql\HasRightJoin;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
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
     * Insert constructor.
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
        $this->query->append(Sql::UPDATE, $this->query->sql()->append(Sql::UPDATE));
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
            $this->query->append(Sql::WHERE, $this->filter->build([Sql::WHERE]));
        }

        if (in_array(Sql::JOIN, $clauses) && $this->onFilter) {
            $this->query->append(Sql::JOIN, $this->onFilter->build([Sql::JOIN]));
        }

        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }

    private $table;

    /**
     * @param RepositoryInterface $table
     * @return UpdateInterface
     */
    public function table(RepositoryInterface $table): UpdateInterface
    {
        $sql = $this->query->sql();
        $sql->append($this->query()->accent()->append($table->getName()))
            ->ifThenAppend(!empty($table->getAlias()), Sql:: AS)
            ->ifThenAppend(!empty($table->getAlias()), $this->query()->accent()->append($table->getAlias()));

        $this->query->append(Sql::UPDATE, $sql);

        $this->table = $table;
        $this->setFilter($table->filter());
        $this->setOnFilter($table->onFilter());

        return $this;
    }

    /**
     * @param array $values
     * @return UpdateInterface
     */
    public function set(array $values): UpdateInterface
    {
        $sql = $this->query()->sql();
        $sql->ifThenAppend(empty($this->query()->hasClause(Sql::SET)),Sql::SET);
        $sql->ifThenAppend(!empty($this->query()->hasClause(Sql::SET)),",",[],false);

        $query = $this->query();
        $sql->append(implode(",",
            array_map(function ($column) use ($query) {
                return $query->accent()->append($column) . Sql::SQL_SPACE . Sql::SQL_EQUAL . Sql::SQL_QUESTION_MARK;
            }, array_keys($values))
        ), array_values($values), false);

        $this->query()->append(Sql::SET, $query->sql());

        return $this;
    }

    /**
     * @param array $cols
     * @return UpdateInterface
     */
    public function increment(array $cols): UpdateInterface
    {
        $sql = $this->query()->sql();
        $sql->ifThenAppend(empty($this->query()->hasClause(Sql::SET)),Sql::SET);
        $sql->ifThenAppend(!empty($this->query()->hasClause(Sql::SET)),",",[],false);

        return $this;
    }

    /**
     * @param array $cols
     * @return UpdateInterface
     */
    public function decrement(array $cols): UpdateInterface
    {
        $sql = $this->query()->sql();
        $sql->ifThenAppend(empty($this->query()->hasClause(Sql::SET)),Sql::SET);
        $sql->ifThenAppend(!empty($this->query()->hasClause(Sql::SET)),",",[],false);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return UpdateInterface
     */
    public function filter(\Closure $callback): UpdateInterface
    {
        $callback->call($this->filter, $this->table);

        return $this;
    }

    /**
     * @param \Closure $callback
     * @return UpdateInterface
     */
    public function onFilter(\Closure $callback): UpdateInterface
    {
        $callback->call($this->onFilter, $this->table);

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