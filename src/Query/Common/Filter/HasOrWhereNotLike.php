<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotLike
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereNotLike
{
    /**
     * @param FieldInterface $field
     * @param $values
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function orWhereNotLike(FieldInterface $field, $values): FilterInterface
    {
        if ($this instanceof FilterInterface) {
            $this->orWhereNot(
                $field,
                $this->operator()
                    ->param()
                    ->like($values)
            );

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
