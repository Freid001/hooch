<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnInterface;

/**
 * Trait NestedWhere
 * @package QueryMule\Builder\Sql\Common\Clause\Clause
 */
trait HasNestedWhere
{
    use Common;

    /**
     * @param \Closure $callback
     * @return $this
     */
    public function nestedWhere(\Closure $callback)
    {
        $this->logical()->setNested(true);

        call_user_func($callback, $query = $this);

        if($this instanceof OnInterface){
            $this->query()->add(Sql::JOIN, new Sql(Sql::SQL_BRACKET_CLOSE));
        }else {
            $this->query()->add(Sql::WHERE, new Sql(Sql::SQL_BRACKET_CLOSE));
        }

        return $this;
    }
}
