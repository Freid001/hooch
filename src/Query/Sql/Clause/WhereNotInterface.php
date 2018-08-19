<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Interface WhereNotInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotInterface
{
    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return mixed
     */
    public function whereNot($column, ?Comparison $comparison = null, ?Logical $logical = null);
}