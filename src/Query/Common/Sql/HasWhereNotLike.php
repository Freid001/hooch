<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotLike
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereNotLike
{
    /**
     * @param FieldInterface $field
     * @param $value
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereNotLike(FieldInterface $field, $value): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->whereNot(
                $field,
                $this->operator()
                    ->logical()
                    ->param()
                    ->omitTrailingSpace()
                    ->like($value)
            );

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
