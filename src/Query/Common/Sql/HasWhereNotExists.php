<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotExists
 * @package QueryMule\Query\Common\Sql
 */
trait HasWhereNotExists
{
    /**
     * @param Sql $subQuery
     * @return FilterInterface
     * @throws SqlException
     */
    public function whereNotExists(Sql $subQuery): FilterInterface
    {
        if($this instanceof FilterInterface) {
            $this->whereNot(null, $this->query()->logical()->omitTrailingSpace()->exists($subQuery));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
