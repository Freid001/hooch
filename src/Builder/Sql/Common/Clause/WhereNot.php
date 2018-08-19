<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Clause\WhereNotInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class WhereNot
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereNot implements WhereNotInterface
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
     * WhereNot constructor.
     * @param WhereInterface $clause
     * @param Logical $logical
     */
    public function __construct(WhereInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     */
    public function whereNot($column, ?Comparison $comparison = null, ?Logical $logical = null): void
    {
        $column = is_string($column) ? $this->addAccent($column, '.') : $column;

        $this->clause->where(null, null, $this->logical->not($column, $comparison, $logical));
    }
}
