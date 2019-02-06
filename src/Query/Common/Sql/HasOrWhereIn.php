<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasOrWhereIn
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrWhereIn
{
    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     * @throws SqlException
     */
    public function orWhereIn($column, array $values = []): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->orWhere($column, $this->query()->logical()->omitTrailingSpace()->in($values));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
