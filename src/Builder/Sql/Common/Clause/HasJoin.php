<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnInterface;

/**
 * Trait HasJoin
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasJoin
{
    use Common;

    /**
     * @return OnInterface
     */
    abstract public function on(): OnInterface;

    /**
     * @param $type
     * @param RepositoryInterface $table
     * @param $column
     * @param null|Comparison $comparison
     * @return $this
     */
    public function join($type, RepositoryInterface $table, $column, ?Comparison $comparison = null)
    {
        $this->query()->add(Sql::JOIN,new Sql($type . Sql::SQL_SPACE . $table->getName() . " AS tt"));

        if ($column instanceof \Closure) {
            call_user_func($column, $query = $this->on());
        }else if (!empty($comparison)) {
            $this->query()->add(Sql::JOIN,new Sql($column . Sql::SQL_SPACE));
            $this->query()->add(Sql::JOIN,$comparison->build());
        }

        return $this;
    }
}