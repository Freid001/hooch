<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereIn
 * @package QueryMule\Query\Common\Sql
 */
trait HasWhereIn
{
    /**
     * @param $column
     * @param array $values
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereIn($column, array $values = []): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->where($column, $this->query()->logical()->omitTrailingSpace()->in($values));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
