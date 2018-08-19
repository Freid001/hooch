<?php

namespace QueryMule\Query\Sql\Clause;


/**
 * Interface WhereNotBetweenInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotBetweenInterface
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return mixed
     */
    public function whereNotBetween($column, $from, $to);
}