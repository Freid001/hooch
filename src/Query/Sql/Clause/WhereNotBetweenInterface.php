<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

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
     * @return FilterInterface
     */
    public function whereNotBetween($column, $from, $to);
}