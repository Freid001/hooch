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
     * @param string|null $alias
     * @return $this
     */
    public function groupBy($column, ?string $alias = null)
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
        $sql->ifThenAppend(!$comma,Sql::GROUP);
        $sql->ifThenAppend($comma,',',[],false);
        $sql->ifThenAppend(!empty($alias),$alias.'.',[],false);
        $sql->append($column);

        return $sql;
    }
}
