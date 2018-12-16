<?php

namespace QueryMule\Query\Sql\Clause;


use QueryMule\Query\Sql\Operator\OperatorInterface;

/**
 * Interface WhereNotInterface
 * @package QueryMule\Query\Sql\Clause
 */
interface WhereNotInterface
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return mixed
     */
    public function whereNot($column, OperatorInterface $operator);
}