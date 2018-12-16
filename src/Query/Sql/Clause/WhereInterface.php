<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\OperatorInterface;

/**
 * Interface WhereInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereInterface
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return mixed
     */
    public function where($column, OperatorInterface $operator);
}