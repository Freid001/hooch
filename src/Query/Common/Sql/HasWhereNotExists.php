<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

/**
 * Trait HasWhereNotExists
 * @package Redstraw\Hooch\Query\Common\Sql
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
