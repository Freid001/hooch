<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasNestedWhere
 * @package QueryMule\Query\Common\Sql
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

            call_user_func($callback, $query = $this);

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
