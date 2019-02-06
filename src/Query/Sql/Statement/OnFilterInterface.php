<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Sql\Statement;


use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;

/**
 * Interface OnFilterInterface
 * @package Redstraw\Hooch\Query\Sql\Statement
 */
interface OnFilterInterface extends FilterInterface
{
    /**
     * @param $column
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     */
    public function on($column, ?OperatorInterface $operator): OnFilterInterface;

    /**
     * @param $column
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     */
    public function orOn($column, ?OperatorInterface $operator): OnFilterInterface;
}