<?php

namespace QueryMule\Query\Sql\Clause;


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
     * @return mixed
     */
    public function whereBetween($column, $from, $to);
}