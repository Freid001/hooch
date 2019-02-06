<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotLike
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasWhereNotLike
{
    /**
     * @param $column
     * @param $value
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereNotLike($column, $value): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->whereNot($column, $this->query()->logical()->omitTrailingSpace()->like($value));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
