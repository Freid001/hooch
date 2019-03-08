<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasOrWhere
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhere
{
    /**
     * @param FieldInterface|null $field
     * @param OperatorInterface $operator
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function orWhere(?FieldInterface $field, OperatorInterface $operator): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where(
                null,
                $this->operator()->field()->or($field, $operator)
            );

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
