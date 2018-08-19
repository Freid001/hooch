<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Interface WhereLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function whereLike($column, $value);
}