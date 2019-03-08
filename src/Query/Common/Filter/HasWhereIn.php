<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasWhereIn
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereIn
{
    /**
     * @param FieldInterface $field
     * @param array $values
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function whereIn(FieldInterface $field, array $values = []): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where(
                $field,
                $this->operator()->param()->in($values)
            );

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
