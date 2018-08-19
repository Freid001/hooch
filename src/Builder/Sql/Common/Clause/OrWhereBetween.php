<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\OrWhereBetweenInterface;
use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereBetween
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereBetween implements OrWhereBetweenInterface
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
     * OrWhereBetween constructor.
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
     * @param $from
     * @param $to
     */
    public function orWhereBetween($column, $from, $to): void
    {
        $this->clause->orWhere($column, null, $this->logical->between($from, $to));
    }
}
