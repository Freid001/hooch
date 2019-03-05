<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Statement;


use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;

/**
 * Interface OnFilterInterface
 * @package Redstraw\Hooch\Query\Sql\Statement
 */
interface OnFilterInterface extends FilterInterface
{
    /**
     * @param FieldInterface|\Closure $field
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     */
    public function on($field, ?OperatorInterface $operator = null): OnFilterInterface;

    /**
     * @param FieldInterface|\Closure $field
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     */
    public function orOn($field, ?OperatorInterface $operator = null): OnFilterInterface;
}