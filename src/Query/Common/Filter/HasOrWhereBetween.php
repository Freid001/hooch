<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasOrWhereBetween
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereBetween
{
    /**
     * @param FieldInterface $field
     * @param mixed $from
     * @param mixed $to
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function orWhereBetween(FieldInterface $field, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhere(
                $field,
                $this->operator()
                    ->param()
                    ->between($from, $to)
            );

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
