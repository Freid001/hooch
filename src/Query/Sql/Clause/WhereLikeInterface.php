<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface WhereLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereLike($column, $value);
}