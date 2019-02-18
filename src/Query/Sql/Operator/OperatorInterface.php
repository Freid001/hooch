<?php

namespace Redstraw\Hooch\Query\Sql\Operator;


use Redstraw\Hooch\Query\Sql\Sql;

/**
 * Interface OperatorInterface
 * @package Redstraw\Hooch\Query
 */
interface OperatorInterface
{
    /**
     * @return Sql
     */
    public function build(): Sql;

    /**
     * @return string
     */
    public function getOperator(): string;
}
