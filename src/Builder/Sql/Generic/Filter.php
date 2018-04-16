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
        if(!empty($accent)) {
            $this->setAccent($accent);
        }
    }

    /**
     * @param bool $ignore
     * @return FilterInterface
     */
    public function ignoreAccent($ignore = true) : FilterInterface
    {
        $this->ignoreAccentSymbol($ignore);

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison|null $operator
     * @param null $value
     * @param string $clause
     * @return FilterInterface
     */
    public function where($column, ?Comparison $operator = null, $value = null, $clause = self::WHERE) : FilterInterface
    {
        if($clause == self::WHERE && !empty($this->queryGet(self::WHERE))) {
            $clause = self::AND;
        }

        $column = ($column instanceof \Closure) ? $column : $this->addAccent($column, '.');

        if($column instanceof \Closure) {
            $this->queryAdd(self::WHERE, $this->nestedWhereClause($column));

            return $this;
        }

        $this->queryAdd(self::WHERE, $this->whereClause($column, $operator, $value, $clause));

        return $this;
    }

    /**
     * @param $column
     * @param null|Comparison|null $operator
     * @param null $value
     * @return FilterInterface
     */
    public function orWhere($column, ?Comparison $operator = null, $value = null) : FilterInterface
    {
        $this->where($column, $operator, $value, self::OR);

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column,array $values = []) : FilterInterface
    {
        $this->where($column, null, $this->whereClause($column, null, $values, FilterInterface::IN));

        return $this;
    }

    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column,array $values = []) : FilterInterface
    {
        $this->orWhere($column, null, $this->whereClause($column, null, $values, FilterInterface::IN));

        return $this;
    }

    public function whereLogical($column, ?Logical $logical = null)
    {
        $this->whereClause($column, null, $logical);
    }

    /**
     * @param $column
     * @param null|Comparison $operator
     * @param null $value
     * @return FilterInterface
     */
    public function whereNot($column, ?Comparison $operator = null, $value = null) : FilterInterface
    {
        $this->where($column, null, $this->whereClause($column, $operator, $value, FilterInterface::NOT));

        return $this;
    }

    public function orWhereNot()
    {}


    public function whereLike()
    {}

    public function whereBetween()
    {}

    public function whereNotBetween()
    {}

    public function whereExists()
    {}

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        self::WHERE
    ]) : Sql
    {
        $sql = $this->queryBuild($clauses);

        $this->queryReset($clauses);

        return $sql;
    }
}
