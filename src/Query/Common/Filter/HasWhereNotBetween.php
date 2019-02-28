<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field\FieldInterface;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Trait HasWhereNotBetween
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereNotBetween
{
    /**
     * @param FieldInterface $field
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function whereNotBetween(FieldInterface $field, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->whereNot(
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
