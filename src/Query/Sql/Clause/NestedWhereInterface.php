<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Interface NestedWhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface NestedWhereInterface
{
    /**
     * @param \Closure $callback
     * @return FilterInterface
     */
    public function nestedWhere(\Closure $callback);
}