<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereBetween
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereBetween
{
    /**
     * @param FieldInterface $field
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereBetween(FieldInterface $field, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhere(
                $field,
                $this->operator()
                    ->logical()
                    ->param()
                    ->omitTrailingSpace()
                    ->between($from, $to)
            );

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
