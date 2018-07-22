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
class Select implements SelectInterface
{
    use Accent;
    use Query;
    use HasFromClause;
    use HasColumnClause;
    use HasJoinClause;
    use HasGroupByClause;
    use HasOrderByClause;
    use HasLimitClause;
    use HasOffsetClause;
    use HasUnionClause;

    /**
     * @var FilterInterface
     */
    protected $filter;

    /**
     * Select constructor.
     * @param array $cols
     * @param RepositoryInterface|null $table
     * @param string|null $accent
     */
    public function __construct(array $cols = [], RepositoryInterface $table = null, $accent = null)
    {
        if (!empty($cols)) {
            $this->cols($cols);
        }

        if (!empty($table)) {
            $this->from($table);
        }

        if (!empty($accent)) {
            $this->setAccent($accent);
        }

        $this->queryAdd(Sql::SELECT, new Sql(Sql::SELECT));
    }

    /**
     * @param $column
     * @param array $values
     * @return SelectInterface
     */
    public function WhereNotIn($column, array $values = []): SelectInterface
    {
        $this->filter->whereNotIn($column, $values);

        return $this;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::SELECT,   // DONE
        Sql::COLS,     // DONE
        Sql::FROM,     // DONE
        Sql::JOIN,     // <<<
        Sql::WHERE,    // DONE
        Sql::GROUP,    // REFACTOR
        Sql::ORDER,    // REFACTOR
        Sql::HAVING,   // <<<
        Sql::LIMIT,    // DONE
        Sql::OFFSET,   // DONE
        Sql::UNION     // DONE
    ]): Sql
    {
        if (in_array(Sql::WHERE, $clauses)) {
            $this->queryAdd(Sql::WHERE, $this->filter->build([Sql::WHERE]));
        }

        $sql = $this->queryBuild($clauses);

        $this->queryReset($clauses);

        return $sql;
    }

    /**
     * @param array $cols
     * @param null $alias
     * @return SelectInterface
     */
    public function cols($cols = [Sql::SQL_STAR], $alias = null): SelectInterface
    {
        $i = 0;
        foreach ($cols as $key => &$col) {
            if ((int)$key !== $i) {
                $i++; //Increment only when we using int positions
            }

            $sql = $this->columnClause(
                ($col !== Sql::SQL_STAR) ? $this->addAccent($col) : $col,
                !empty($alias) ? $this->addAccent($alias) : $alias,
                ($key !== $i) ? $key : null,
                !empty($this->queryGet(Sql::COLS))
            );

            $this->queryAdd(Sql::COLS, $sql);
        }

        return $this;
    }

    /**
     * @return Comparison
     */
    public function comparison(): Comparison
    {
        return new Comparison();
    }

    /**
     * @param RepositoryInterface $table
     * @param null $alias
     * @return SelectInterface
     */
    public function from(RepositoryInterface $table, $alias = null): SelectInterface
    {
        $this->queryAdd(Sql::FROM, $this->fromClause($table, $alias));

        $this->filter = $table->filter();

        return $this;
    }

    /**
     * @param string $column
     * @param string|null $alias
     * @return SelectInterface
     */
    public function groupBy($column, $alias = null): SelectInterface
    {
        $sql = $this->groupByClause(
            $this->addAccent($column),
            !empty($alias) ? $this->addAccent($alias) : $alias,
            !empty($this->queryGet(Sql::GROUP))
        );

        $this->queryAdd(Sql::GROUP, $sql);

        return $this;
    }

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true): SelectInterface
    {
        $this->ignoreAccentSymbol($ignore);

        if (!empty($this->filter)) {
            $this->filter->ignoreAccent($ignore);
        }

        return $this;
    }

    /**
     * @param int $limit
     * @return SelectInterface
     */
    public function limit(int $limit): SelectInterface
    {
        $this->queryAdd(Sql::LIMIT, $this->limitClause($limit));

        return $this;
    }

    /**
     * @return Logical
     */
    public function logical(): Logical
    {
        return new Logical();
    }

    public function nestedWhere(\Closure $column)
    {
        $this->filter->nestedWhere($column);

        return $this;
    }

    /**
     * @param int $offset
     * @return SelectInterface
     */
    public function offset(int $offset): SelectInterface
    {
        $this->queryAdd(Sql::OFFSET, $this->offsetClause($offset));

        return $this;
    }

    public function on($first, $operator, $second): SelectInterface
    {
        $this->queryAdd(Sql::JOIN, $this->onClause($first, $operator, $second, Sql::ON));

        return $this;
    }

    public function orOn($first, $operator = null, $second = null): SelectInterface
    {
        $this->queryAdd(Sql::JOIN, $this->onClause($first, $operator, $second, Sql:: OR));

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return SelectInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null): SelectInterface
    {
        $this->filter->orWhere($column, $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return SelectInterface
     */
    public function orWhereBetween($column, $from, $to): SelectInterface
    {
        $this->filter->orWhereBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery): FilterInterface
    {
        $this->filter->orWhereExists($subQuery);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return SelectInterface
     */
    public function orWhereIn($column, array $values = []): SelectInterface
    {
        $this->filter->orWhereIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereLike($column, $value): FilterInterface
    {
        $this->filter->orWhereLike($column, $value);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return SelectInterface
     */
    public function orWhereNot($column, ?Comparison $comparison = null, ?Logical $logical = null): SelectInterface
    {
        $this->filter->orWhereNot($column, $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return SelectInterface
     */
    public function orWhereNotBetween($column, $from, $to): SelectInterface
    {
        $this->filter->orWhereNotBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereNotExists(Sql $subQuery): FilterInterface
    {
        $this->filter->orWhereNotExists($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return SelectInterface
     */
    public function orWhereNotIn($column, array $values = []): SelectInterface
    {
        $this->filter->orwhereNotIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereNotLike($column, $value): FilterInterface
    {
        $this->filter->orWhereNotLike($column, $value);

        return $this;
    }

    /**
     * @param string $column
     * @param string $sort
     * @param null|string $alias
     * @return SelectInterface
     */
    public function orderBy($column, string $sort = 'desc', ?string $alias = null): SelectInterface
    {
        $sql = $this->orderByClause(
            $this->addAccent($column),
            $sort,
            !empty($alias) ? $this->addAccent($alias) : $alias,
            !empty($this->queryGet(Sql::ORDER))
        );

        $this->queryAdd(Sql::ORDER, $sql);

        return $this;
    }

    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return SelectInterface
     */
    public function union(SelectInterface $select, bool $all = false): SelectInterface
    {
        $this->queryAdd(Sql::UNION, $this->unionClause($select, $all));

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return SelectInterface
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null): SelectInterface
    {
        $this->filter->where($column, $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return SelectInterface
     */
    public function whereBetween($column, $from, $to): SelectInterface
    {
        $this->filter->whereBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery): FilterInterface
    {
        $this->filter->whereExists($subQuery);

        return $this;
    }

    /**
     * @param string $column
     * @param array $values
     * @return SelectInterface
     */
    public function whereIn($column, array $values = []): SelectInterface
    {
        $this->filter->whereIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereLike($column, $value): FilterInterface
    {
        $this->filter->whereLike($column, $value);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $comparison = null, ?Logical $logical = null): FilterInterface
    {
        $this->filter->whereNot($column, $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return SelectInterface
     */
    public function whereNotBetween($column, $from, $to): SelectInterface
    {
        $this->filter->whereNotBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereNotExists(Sql $subQuery): FilterInterface
    {
        $this->filter->whereNotExists($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereNotLike($column, $value): FilterInterface
    {
        $this->filter->whereNotLike($column, $value);

        return $this;
    }

    public function having()
    {
    }

    /**
     * @param RepositoryInterface $table
     * @param null|string $alias
     * @param null|string $column
     * @param null|Comparison $comparison
     * @return SelectInterface
     * @throws SqlException
     */
    public function join(RepositoryInterface $table, ?string $alias, $column, ?Comparison $comparison): SelectInterface
    {

        $this->queryAdd(Sql::JOIN, $this->joinClause(Sql::JOIN_LEFT, $table, $alias));


//        if ($table instanceof RepositoryInterface) {
//            $this->queryAdd(Sql::JOIN, $this->joinClause(Sql::JOIN_LET, $table, $alias));
//            return $this->on($first, $operator, $second);
//        } else {
//            throw new SqlException('Table must be instance of RepositoryInterface');
//        }
    }
}
