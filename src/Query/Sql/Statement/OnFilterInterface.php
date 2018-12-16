<?php

namespace QueryMule\Query\Sql\Statement;


use QueryMule\Query\Sql\Operator\Comparison;

/**
 * Interface OnInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface OnFilterInterface
{
    /**
     * @param $column
     * @param null|Comparison $comparison
     * @return $this
     */
    public function on($column, ?Comparison $comparison);

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @return mixed
     */
    public function orOn($column, ?Comparison $comparison);
}