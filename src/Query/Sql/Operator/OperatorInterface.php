<?php

namespace QueryMule\Query\Sql\Operator;

/**
 * Interface OperatorInterface
 * @package QueryMule\Query
 */
interface OperatorInterface
{
    /**
     * @return String|null
     */
    public function getOperator(): ?String;
}