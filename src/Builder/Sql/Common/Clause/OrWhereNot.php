<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereNot
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereNot implements OrWhereNotInterface
{
    /**
     * @var WhereInterface
     */
    private $clause;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * OrWhereNot constructor.
     * @param OrWhereInterface $clause
     * @param Logical $logical
     */
    public function __construct(OrWhereInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     */
    public function orWhereNot($column, ?Comparison $comparison = null, ?Logical $logical = null): void
    {
        $column = is_string($column) ? $this->addAccent($column, '.') : $column;

        $this->clause->orWhere(null, null, $this->logical->not($column, $comparison, $logical));
    }
}
