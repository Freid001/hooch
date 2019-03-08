<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotIn
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereNotIn
{
    /**
     * @param FieldInterface $field
     * @param array $values
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function orWhereNotIn(FieldInterface $field, array $values = []): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot(
                $field,
                $this->operator()->param()->in($values)
            );

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
