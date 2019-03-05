<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasWhereLike
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereLike
{
    /**
     * @param FieldInterface $field
     * @param mixed $value
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function whereLike(FieldInterface $field, $value): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where(
                $field,
                $this->operator()
                    ->param()
                    ->like($value)
            );

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
