<?php

namespace QueryMule\Query\Sql\Clause;


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
     * @return mixed
     */
    public function orWhereNotBetween($column, $from, $to);
}