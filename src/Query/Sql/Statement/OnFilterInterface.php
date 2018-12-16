<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Query\Sql\Operator\OperatorInterface;

/**
 * Interface OnInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface OnFilterInterface
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return $this|OnFilterInterface
     */
    public function on($column, OperatorInterface $operator);

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return $this|OnFilterInterface
     */
    public function orOn($column, OperatorInterface $operator);
}