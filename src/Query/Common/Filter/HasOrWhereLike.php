<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasOrWhereLike
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereLike
{
    /**
     * @param FieldInterface $field
     * @param mixed $value
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function orWhereLike(FieldInterface $field, $value): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhere(
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
