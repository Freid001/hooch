<?php

namespace QueryMule\Query\Sql\Operator;

use QueryMule\Query\QueryBuilderInterface;

/**
 * Interface OperatorInterface
 * @package QueryMule\Query
 */
interface OperatorInterface  //extends QueryBuilderInterface
{
    /**
     * @return String|null
     */
    public function getOperator(): ?String;
}