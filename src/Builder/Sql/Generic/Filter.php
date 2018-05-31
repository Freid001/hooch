<?php

namespace QueryMule\Builder\Sql\Generic;

use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class Filter
 * @package QueryMule\Builder\Sql\Generic
 */
class Filter implements FilterInterface
{
    use Accent;
    use Query;

    use HasWhereClause;

    /**
     * @var Comparison
     */
    private $comparison;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * Filter constructor.
     * @param string $accent
     */
    public function __construct($accent)
    {
        if (!empty($accent)) {
            $this->setAccent($accent);
        }

        $this->comparison = new Comparison();
        $this->logical = new Logical();
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::WHERE
    ]): Sql
    {
        $sql = $this->queryBuild($clauses);

        $this->queryReset($clauses);

        return $sql;
    }

    /**
     * @return Comparison
     */
    public function comparison(): Comparison
    {
        return $this->comparison;
    }

    /**
     * @param bool $ignore
     * @return FilterInterface
     */
    public function ignoreAccent($ignore = true): FilterInterface
    {
        $this->ignoreAccentSymbol($ignore);

        return $this;
    }

    /**
     * @return Logical
     */
    public function logical(): Logical
    {
        return $this->logical;
    }

    /**
     * @param string $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null): FilterInterface
    {
        $column = is_string($column) ? $this->addAccent($column, '.') : $column;

        $this->where(null, null, $this->logical()->or($column, $comparison, $logical));

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
        $this->orWhere($column, null, $this->logical()->between($from, $to));

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereExists(Sql $subQuery): FilterInterface
    {
        $this->orWhere(null, null, $this->logical()->exists($subQuery));

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column, array $values = []): FilterInterface
    {
        $this->orWhere($column, null, $this->logical()->in($values));

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
        $column = is_string($column) ? $this->addAccent($column, '.') : $column;

        $this->orWhere(null, null, $this->logical()->not($column, $comparison, $logical));

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
        $this->orWhereNot($column, null, $this->logical()->between($from, $to));

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function orWhereNotExists(Sql $subQuery): FilterInterface
    {
        $this->orWhereNot(null, null, $this->logical()->exists($subQuery));

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereNotIn($column, array $values = []): FilterInterface
    {
        $this->orWhereNot($column, null, $this->logical()->in($values));

        return $this;
    }

    /**
     * @param string $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null): FilterInterface
    {
        $column = is_string($column) ? $this->addAccent($column, '.') : $column;

        $and = false;
        if ((is_null($logical) || $logical->getOperator() != Sql:: OR) && !empty($this->queryGet(Sql::WHERE))) {
            $and = true;
            $logical = $this->logical()->and($column, $comparison, $logical);
        }

        $this->queryAdd(Sql::WHERE, $this->whereClause(
            !$and ? $column : null,
            !$and ? $comparison : null,
            $logical
        ));

        return $this;
    }

    public function nestedWhere(\Closure $column): FilterInterface
    {
        $this->setNested(true);
        $this->logical()->setNested(true);

        $column($this);

        $this->queryAdd(Sql::WHERE, new Sql($this->setNested(true)->nested(false)));

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
        $this->where($column, null, $this->logical()->between($from, $to));

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereExists(Sql $subQuery): FilterInterface
    {
        $this->where(null, null, $this->logical()->exists($subQuery));

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column, array $values = []): FilterInterface
    {
        $this->where($column, null, $this->logical()->in($values));

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
        $column = is_string($column) ? $this->addAccent($column, '.') : $column;

        $this->where(null, null, $this->logical()->not($column, $comparison, $logical));

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
        $this->whereNot($column, null, $this->logical()->between($from, $to));

        return $this;
    }

    /**
     * @param Sql $subQuery
     * @return FilterInterface
     */
    public function whereNotExists(Sql $subQuery): FilterInterface
    {
        $this->whereNot(null, null, $this->logical()->exists($subQuery));

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereNotIn($column, array $values = []): FilterInterface
    {
        $this->whereNot($column, null, $this->logical()->in($values));

        return $this;
    }
}
