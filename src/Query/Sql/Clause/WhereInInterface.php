<?php

namespace QueryMule\Query\Sql\Clause;


/**
 * Interface WhereInInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereInInterface
{
    /**
     * @param $column
     * @param array $values
     * @return mixed
     */
    public function whereIn($column, array $values = []);
}