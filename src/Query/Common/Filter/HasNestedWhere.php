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
                $this->query()->clause(Sql::JOIN, function (Sql $sql) {
                    return $sql->append(Sql::SQL_BRACKET_CLOSE);
                });
            }else {
                $this->query()->clause(Sql::WHERE, function (Sql $sql) {
                    return $sql->append(Sql::SQL_BRACKET_CLOSE);
                });
            }

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}