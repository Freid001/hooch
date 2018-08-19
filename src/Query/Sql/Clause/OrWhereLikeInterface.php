<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Interface OrWhereLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function orWhereLike($column, $value);
}