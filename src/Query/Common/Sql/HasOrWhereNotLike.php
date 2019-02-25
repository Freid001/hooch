<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

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
     * @throws SqlException
     */
    public function orWhereNotLike(FieldInterface $field, $values): FilterInterface
    {
        if ($this instanceof FilterInterface) {
            $this->orWhereNot(
                $field,
                $this->operator()
                    ->logical()
                    ->param()
                    ->omitTrailingSpace()
                    ->like($values)
            );

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
