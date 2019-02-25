<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Field\FieldInterface;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

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
     * @throws SqlException
     */
    public function orWhereNotIn(FieldInterface $field, array $values = []): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhereNot(
                $field,
                $this->operator()
                    ->logical()
                    ->param()
                    ->omitTrailingSpace()
                    ->in($values)
            );

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
