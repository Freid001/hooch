<?php

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Builder\Sql\Common\Clause\HasCols;
use QueryMule\Builder\Sql\Common\Clause\HasFrom;
use QueryMule\Builder\Sql\Common\Clause\HasGroupBy;
use QueryMule\Builder\Sql\Common\Clause\HasJoin;
use QueryMule\Builder\Sql\Common\Clause\HasLimit;
use QueryMule\Builder\Sql\Common\Clause\HasOffset;
use QueryMule\Builder\Sql\Common\Clause\HasOrderBy;
use QueryMule\Builder\Sql\Common\Clause\HasUnion;
use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Sqlite
 */
class Select implements QueryBuilderInterface, SelectInterface
{
    use HasCols;
    use HasFrom;
    use HasGroupBy;
    use HasLimit;
    use HasOffset;
    use HasUnion;
    use HasOrderBy;
    use HasJoin;

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * @var Query
     */
    private $query;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * Select constructor.
     * @param array $cols
     * @param RepositoryInterface|null $table
     * @param null $accent
     */
    public function __construct(array $cols = [], RepositoryInterface $table = null, $accent = null)
    {
        $this->query = new Query();
        $this->logical = new Logical();
        $this->accent = new Accent();
        $this->accent->setSymbol('`');

        if (!empty($cols)) {
            $this->cols($cols);
        }

        if (!empty($table)) {
            $this->from($table);
        }

        $this->query->add(Sql::SELECT, new Sql(Sql::SELECT));
    }

    /**
     * @return Accent
     */
    public function accent(): Accent
    {
        return $this->accent;
    }

    /**
     * @return Logical
     */
    public function logical(): Logical
    {
        return $this->logical;
    }

    /**
     * @return Query
     */
    public function query(): Query
    {
        return $this->query;
    }

    /**
     * @return OnInterface
     */
    public function on(): OnInterface
    {
        return new On($this->query(),$this->logical());
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
        Sql::GROUP,    // DONE
        Sql::ORDER,    // DONE
        Sql::HAVING,   // <<<
        Sql::LIMIT,    // DONE
        Sql::OFFSET,   // DONE
        Sql::UNION     // DONE
    ]): Sql
    {
        if (in_array(Sql::WHERE, $clauses)) {
            $this->query->add(Sql::WHERE, $this->filter->build([Sql::WHERE]));
        }

        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }

    /**
     * @param \Closure $column
     * @return $this|FilterInterface
     */
    public function nestedWhere(\Closure $column)
    {
        $this->filter->nestedWhere($column);

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
     * @return SelectInterface
     */
    public function orWhereExists(Sql $subQuery): SelectInterface
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
     * @return SelectInterface
     */
    public function orWhereLike($column, $value): SelectInterface
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
     * @return SelectInterface
     */
    public function orWhereNotExists(Sql $subQuery): SelectInterface
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
     * @return SelectInterface
     */
    public function orWhereNotLike($column, $value): SelectInterface
    {
        $this->filter->orWhereNotLike($column, $value);

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
     * @return SelectInterface
     */
    public function whereExists(Sql $subQuery): SelectInterface
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
     * @return SelectInterface
     */
    public function whereLike($column, $value): SelectInterface
    {
        $this->filter->whereLike($column, $value);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return SelectInterface
     */
    public function whereNot($column, ?Comparison $comparison = null, ?Logical $logical = null): SelectInterface
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
     * @return SelectInterface
     */
    public function whereNotExists(Sql $subQuery): SelectInterface
    {
        $this->filter->whereNotExists($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return SelectInterface
     */
    public function whereNotIn($column, array $values = []): SelectInterface
    {
        $this->filter->whereNotIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return SelectInterface
     */
    public function whereNotLike($column, $value): SelectInterface
    {
        $this->filter->whereNotLike($column, $value);

        return $this;
    }

    /**
     * @param bool $ignore
     * @return $this|SelectInterface
     */
    public function ignoreAccent($ignore = true)
    {
        $this->accent->ignore($ignore);

        if (!empty($this->filter)) {
            $this->filter->accent()->ignore($ignore);
        }

        return $this;
    }

    /**
     * @param $filter
     */
    public function setFilter($filter)
    {
        $this->filter = $filter;
    }
}