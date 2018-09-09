<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasGroupBy
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasGroupBy
{
    use Common;

    /**
     * @param $column
     * @param null $alias
     * @return $this
     */
    public function groupBy($column, $alias = null)
    {
        $sql = $this->groupByClause(
            $this->accent()->append($column),
            !empty($alias) ? $this->accent()->append($alias) : $alias,
            !empty($this->query()->get(Sql::GROUP))
        );

        $this->query()->add(Sql::GROUP, $sql);

        return $this;
    }

    /**
     * @param string $column
     * @param bool $alias
     * @param bool $comma
     * @return Sql
     */
    private function groupByClause($column, $alias = false, $comma = false)
    {
        $sql = '';

        if($comma) {
            $sql .= ',';
            $sql .= !empty($alias) ? $alias.'.'.$column : $column;
        }else {
            $sql = Sql::GROUP;
            $sql .= !empty($alias) ? ' '.$alias.'.'.$column : ' '.$column;
        }

        return new Sql($sql);
    }
}
