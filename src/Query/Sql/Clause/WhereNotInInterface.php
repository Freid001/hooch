<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface WhereNotInInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotInInterface
{
    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereNotIn($column, array $values = []);
}