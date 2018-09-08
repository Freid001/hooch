<?php

namespace QueryMule\Builder\Sql\Mysql;

use QueryMule\Builder\Sql\Common\Clause\NestedWhere;
use QueryMule\Builder\Sql\Common\Clause\OrWhere;
use QueryMule\Builder\Sql\Common\Clause\OrWhereBetween;
use QueryMule\Builder\Sql\Common\Clause\OrWhereExists;
use QueryMule\Builder\Sql\Common\Clause\OrWhereIn;
use QueryMule\Builder\Sql\Common\Clause\OrWhereLike;
use QueryMule\Builder\Sql\Common\Clause\OrWhereNot;
use QueryMule\Builder\Sql\Common\Clause\OrWhereNotBetween;
use QueryMule\Builder\Sql\Common\Clause\OrWhereNotExists;
use QueryMule\Builder\Sql\Common\Clause\OrWhereNotLike;
use QueryMule\Builder\Sql\Common\Clause\Where;
use QueryMule\Builder\Sql\Common\Clause\WhereBetween;
use QueryMule\Builder\Sql\Common\Clause\WhereExists;
use QueryMule\Builder\Sql\Common\Clause\WhereIn;
use QueryMule\Builder\Sql\Common\Clause\WhereLike;
use QueryMule\Builder\Sql\Common\Clause\WhereNot;
use QueryMule\Builder\Sql\Common\Clause\WhereNotBetween;
use QueryMule\Builder\Sql\Common\Clause\WhereNotExists;
use QueryMule\Builder\Sql\Common\Clause\WhereNotIn;
use QueryMule\Builder\Sql\Common\Clause\WhereNotLike;
use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\QueryBuilderInterface;

/**
 * Class Filter
 * @package QueryMule\Builder\Sql\Mysql
 */
class Filter implements FilterInterface
{
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
     * Filter constructor.
     * @param Query $query
     * @param Logical $logical
     */
    public function __construct(Query $query, Logical $logical)
    {
        $this->query = $query;
        $this->logical = $logical;
        $this->accent = new Accent();
        $this->accent->setSymbol('`');
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::WHERE
    ]): Sql
    {
        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }

    /**
     * @param \Closure $callback
     * @return FilterInterface
     */
    public function nestedWhere(\Closure $callback): FilterInterface
    {
        $common = new NestedWhere($this->query, new Filter($this->query, $this->logical->setNested(true)));
        $common->nestedWhere($callback);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null): FilterInterface
    {
        $common = new OrWhere($this, $this->logical);
        $common->orWhere($this->accent->append($column, '.'), $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereBetween($column, $from, $to): FilterInterface
    {
        $common = new OrWhereBetween($this, $this->logical);
        $common->orWhereBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery): FilterInterface
    {
        $common = new OrWhereExists($this, $this->logical);
        $common->orWhereExists($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column, array $values = []): FilterInterface
    {
        $common = new OrWhereIn($this, $this->logical);
        $common->orWhereIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereLike($column, $value): FilterInterface
    {
        $common = new OrWhereLike($this, $this->logical);
        $common->orWhereLike($column, $value);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function orWhereNot($column, ?Comparison $comparison = null, ?Logical $logical = null): FilterInterface
    {
        $common = new OrWhereNot($this, $this->logical);
        $common->orWhereNot($column, $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereNotBetween($column, $from, $to): FilterInterface
    {
        $common = new OrWhereNotBetween($this, $this->logical);
        $common->orWhereNotBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereNotExists(Sql $subQuery): FilterInterface
    {
        $common = new OrWhereNotExists($this, $this->logical);
        $common->orWhereNotExists($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereNotLike($column, $value): FilterInterface
    {
        $common = new OrWhereNotLike($this, $this->logical);
        $common->orWhereNotLike($column, $value);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null): FilterInterface
    {
        $common = new Where($this->query, $this->logical);
        $common->where($this->accent->append($column, '.'), $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereBetween($column, $from, $to): FilterInterface
    {
        $common = new WhereBetween($this, $this->logical);
        $common->whereBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery): FilterInterface
    {
        $common = new WhereExists($this, $this->logical);
        $common->whereExists($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column, array $values = []): FilterInterface
    {
        $common = new WhereIn($this, $this->logical);
        $common->whereIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereLike($column, $value): FilterInterface
    {
        $common = new WhereLike($this, $this->logical);
        $common->whereLike($column, $value);

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
        $common = new WhereNot($this, $this->logical);
        $common->whereNot($column, $comparison, $logical);

        return $this;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereNotBetween($column, $from, $to): FilterInterface
    {
        $common = new WhereNotBetween($this, $this->logical);
        $common->whereNotBetween($column, $from, $to);

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereNotExists(Sql $subQuery): FilterInterface
    {
        $common = new WhereNotExists($this, $this->logical);
        $common->whereNotExists($subQuery);

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereNotIn($column, array $values = []): FilterInterface
    {
        $common = new WhereNotIn($this, $this->logical);
        $common->whereNotIn($column, $values);

        return $this;
    }

    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereNotLike($column, $value): FilterInterface
    {
        $common = new WhereNotLike($this, $this->logical);
        $common->whereNotLike($column, $value);

        return $this;
    }
}
