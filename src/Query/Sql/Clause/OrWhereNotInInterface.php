<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface OrWhereNotInInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface OrWhereNotInInterface
{
    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function orWhereNotIn($column, array $values = []);
}