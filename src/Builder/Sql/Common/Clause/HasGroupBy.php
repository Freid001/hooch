<?php

declare(strict_types=1);

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
     * @param $column
     * @param bool $alias
     * @param bool $comma
     * @return Sql
     */
    private function groupByClause($column, $alias = false, $comma = false): Sql
    {
        $sql = new Sql();
        $sql->appendIf(!$comma,Sql::GROUP);
        $sql->appendIf($comma,',',[],false);
        $sql->appendIf(!empty($alias),$alias.'.',[],false);
        $sql->append($column);

        return $sql;
    }
}
