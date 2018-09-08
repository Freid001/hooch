<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface WhereBetweenInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereBetweenInterface
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return FilterInterface
     */
    public function whereBetween($column, $from, $to);
}