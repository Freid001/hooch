<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\WhereNotBetweenInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Clause\WhereNotInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereNotBetween
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereNotBetween implements WhereNotBetweenInterface
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
     * @param WhereNotInterface $clause
     * @param Logical $logical
     */
    public function __construct(WhereNotInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param $from
     * @param $to
     */
    public function whereNotBetween($column, $from, $to): void
    {
        $this->clause->whereNot($column, null, $this->logical->between($from, $to));
    }
}
