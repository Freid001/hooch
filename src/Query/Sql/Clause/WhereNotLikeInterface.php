<?php

namespace QueryMule\Query\Sql\Clause;


/**
 * Interface WhereNotLikeInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotLikeInterface
{
    /**
     * @param $column
     * @param $value
     * @return mixed
     */
    public function whereNotLike($column, $value);
}