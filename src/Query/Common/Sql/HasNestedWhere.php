<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasNestedWhere
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasNestedWhere
{
    /**
     * @param \Closure $callback
     * @return FilterInterface
     * @throws SqlException
     */
    public function nestedWhere(\Closure $callback): FilterInterface
    {
        if($this instanceof FilterInterface){
            $this->operator()->logical()->field()->setNested(true);
            $callback->call($this);

            if($this instanceof OnFilterInterface){
                $this->query()->sql()->append(Sql::SQL_BRACKET_CLOSE);
                $this->query()->appendSqlToClause(Sql::JOIN);
            }else {
                $this->query()->sql()->append(Sql::SQL_BRACKET_CLOSE);
                $this->query()->appendSqlToClause(Sql::WHERE);
            }

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
