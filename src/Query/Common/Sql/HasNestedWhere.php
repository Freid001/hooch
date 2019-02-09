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
            $this->query()->logical()->setNested(true);
            $callback->call($this);

            if($this instanceof OnFilterInterface){
                $this->query()->append(Sql::JOIN, $this->query()->sql()->append(Sql::SQL_BRACKET_CLOSE));
            }else {
                $this->query()->append(Sql::WHERE, $this->query()->sql()->append(Sql::SQL_BRACKET_CLOSE));
            }

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
