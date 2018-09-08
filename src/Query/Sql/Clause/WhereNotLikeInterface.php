<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface WhereNotLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function whereNotLike($column, $value);
}