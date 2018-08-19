<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Interface OrWhereNotInInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotInInterface
{
    /**
     * @param $column
     * @param array $values
     * @return mixed
     */
    public function orWhereNotIn($column, array $values = []);
}