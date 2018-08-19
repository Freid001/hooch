<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/***
 * Interface OrWhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotInterface
{
    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return mixed
     */
    public function orWhereNot($column, ?Comparison $comparison = null, ?Logical $logical = null);
}