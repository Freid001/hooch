<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface OrWhereNotLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     */
    public function orWhereNotLike($column, $value);
}