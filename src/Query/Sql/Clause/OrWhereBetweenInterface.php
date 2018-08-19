<?php

namespace QueryMule\Query\Sql\Clause;


/**
 * Interface OrWhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereBetweenInterface
{
    /**
     * @param $column
     * @param $from
     * @param $to
     * @return mixed
     */
    public function orWhereBetween($column, $from, $to);
}