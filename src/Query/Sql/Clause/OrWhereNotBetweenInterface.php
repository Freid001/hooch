<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface OrWhereNotBetweenInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotBetweenInterface
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function orWhereNotBetween($column, $from, $to);
}