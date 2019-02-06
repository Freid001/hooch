<?php

namespace Redstraw\Hooch\Query\Sql\Operator;

/**
 * Interface OperatorInterface
 * @package Redstraw\Hooch\Query
 */
interface OperatorInterface
{
    /**
     * @return String|null
     */
    public function getOperator(): ?String;
}