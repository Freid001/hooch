<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Filter;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\FilterInterface;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;

/**
 * Trait HasNestedWhere
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasNestedWhere
{
    /**
     * @param \Closure $callback
     * @return FilterInterface
     * @throws InterfaceException
     */
    public function nestedWhere(\Closure $callback): FilterInterface
    {
        if($this instanceof FilterInterface){
            $this->operator()->field()->setNested(true);
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
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}