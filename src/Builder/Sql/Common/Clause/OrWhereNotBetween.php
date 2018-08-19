<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotBetweenInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereNotBetween
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereNotBetween implements OrWhereNotBetweenInterface
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
     * OrWhereNotBetween constructor.
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
    public function orWhereNotBetween($column, $from, $to): void
    {
        $this->clause->orWhereNot($column, null, $this->logical->between($from, $to));
    }
}
