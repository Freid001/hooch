<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasOrderBy
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOrderBy
{
    use Common;

    /**
     * @param $column
     * @param $order
     * @return $this
     */
    public function orderBy($column, $order)
    {
        $sql = $this->orderByClause(
            $this->accent()->append($column, '.'),
            $order,
            !empty($this->query()->get(Sql::ORDER))
        );

        $this->query()->add(Sql::ORDER, $sql);

        return $this;
    }

    /**
     * @param $column
     * @param string $sort
     * @param bool $comma
     * @return Sql
     */
    private function orderByClause($column, $sort = SQL::DESC, $comma = false)
    {
        $sql = new Sql();
        $sql->appendIf(!$comma,Sql::ORDER);
        $sql->appendIf(!$comma,Sql::BY);
        $sql->appendIf($comma,',',[],false);
        $sql->append($column);
        $sql->append(strtoupper($sort));

        return $sql;
    }
}
