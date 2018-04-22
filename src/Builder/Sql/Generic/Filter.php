<?php

namespace QueryMule\Builder\Sql\Generic;

use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Sql\Operator\Comparison;
use QueryMule\Sql\Operator\Logical;

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
     * @param $column
     * @param null|Comparison $comparison
     * @param null $value
     * @param null|Logical $logical
     * @return FilterInterface
     */
    public function where($column, ?Comparison $comparison = null, $value = null, ?Logical $logical = null): FilterInterface
    {
        if (empty($logical) && !empty($this->queryGet(Sql::WHERE))) {
            $logical = Logical::and();
        }

        if (!$column instanceof \Closure) {
            $this->queryAdd(Sql::WHERE, $this->whereClause(
                !empty($column) ? $this->addAccent($column, '.') : null,
                $comparison,
                $value,
                $logical
            ));
        } else {
            $this->queryAdd(Sql::WHERE, $this->nestedWhereClause($column));
        }

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison|null $comparison
     * @param null $value
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $comparison = null, $value = null): FilterInterface
    {
        $this->where($column, $comparison, $value, Sql:: OR);

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column, array $values = []): FilterInterface
    {
        $this->where($column, null, $this->whereClause(null, null, $values, Sql::IN));

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column, array $values = []): FilterInterface
    {
        $this->orWhere($column, null, $this->whereClause(null, null, $values, Sql::IN));

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null $value
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $comparison = null, $value = null): FilterInterface
    {
        $this->where(null, null, Logical::not($column, $comparison)->build());

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
