<?php

namespace QueryMule\Query\Sql\Clause;

/***
 * Interface OrWhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereInInterface
{
    /**
     * @param $column
     * @param array $values
     * @return mixed
     */
    public function orWhereIn($column, array $values = []);
}