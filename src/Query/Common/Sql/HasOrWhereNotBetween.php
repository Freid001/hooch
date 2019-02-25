<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotBetween
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereNotBetween
{
    /**
     * @param FieldInterface $field
     * @param $from
     * @param $to
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereNotBetween(FieldInterface $field, $from, $to): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot(
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
