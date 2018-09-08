<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/***
 * Interface OrWhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereInInterface
{
    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereIn($column, array $values = []);
}