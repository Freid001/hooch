<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Query\Sql\Sql;

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
        call_user_func($callback, $query = new Filter($this->query(), $this->logical()->setNested(true)));

        $this->query()->add(Sql::WHERE, new Sql(Sql::SQL_BRACKET_CLOSE));

        return $this;
    }
}
