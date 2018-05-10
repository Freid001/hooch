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
     * Filter constructor.
     * @param string $accent
     */
    public function __construct($accent)
    {
        if (!empty($accent)) {
            $this->setAccent($accent);
        }
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
     * @return Comparison
     */
    public function comparison() : Comparison
    {
        return new Comparison();
    }

    /**
     * @return Logical
     */
    public function logical() : Logical
    {
        return new Logical();
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
        if(is_null($logical) && !empty($this->queryGet(Sql::WHERE))){
            $logical = $this->logical()->and($column,$comparison);
            $and = true;
        }

        if (!$column instanceof \Closure) {
            $this->queryAdd(Sql::WHERE, $this->whereClause(
                !$and ? $column : null,
                !$and ? $comparison : null,
                $logical
            ));
        }else {
            $this->queryAdd(Sql::WHERE, $this->nestedWhereClause($column, $logical));
        }

        return $this;
    }

    /**
     * @param string $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null): FilterInterface
    {
        $this->where(null, null, $this->logical()->or($this->addAccent($column, '.'),$comparison,$logical));

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
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $comparison = null): FilterInterface
    {
        $this->where(null, null, $this->logical()->not($column, $comparison));

        return $this;
    }

    public function orWhereNot()
    {
    }


    public function whereLike()
    {
    }

    public function whereBetween()
    {
    }

    public function whereNotBetween()
    {
    }

    public function whereExists()
    {
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
}
