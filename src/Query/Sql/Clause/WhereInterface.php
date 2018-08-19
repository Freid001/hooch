<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Interface WhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereInterface
{
    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return mixed
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null);
}