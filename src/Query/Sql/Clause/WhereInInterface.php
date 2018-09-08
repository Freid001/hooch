<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface WhereInInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereInInterface
{
    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     */
    public function whereIn($column, array $values = []);
}