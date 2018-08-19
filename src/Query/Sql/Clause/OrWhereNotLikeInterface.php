<?php

namespace QueryMule\Query\Sql\Clause;


/**
 * Interface OrWhereNotLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function orWhereNotLike($column, $value);
}