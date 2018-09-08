<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhere
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhere implements OrWhereInterface
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
     * OrWhere constructor.
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
    public function orWhere($column, ?Comparison $comparison = null, ?Logical $logical = null): void
    {
        $this->clause->where(null, null, $this->logical->or($column, $comparison, $logical));
    }
}
