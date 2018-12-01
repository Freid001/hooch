<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface OrWhereLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereLike($column, $value);
}