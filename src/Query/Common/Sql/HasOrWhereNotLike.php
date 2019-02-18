<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereNotLike
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereNotLike
{
    /**
     * @param string|null $column
     * @param $values
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereNotLike(?string $column, $values): FilterInterface
    {
        if ($this instanceof FilterInterface) {
            $this->orWhereNot(
                $column,
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
