<?php

declare(strict_types=1);

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
     * @param string|null $order
     * @return $this
     */
    public function orderBy($column, ?string $order)
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
    private function orderByClause($column, $sort = SQL::DESC, $comma = false): Sql
    {
        $sql = new Sql();
        $sql->ifThenAppend(!$comma,Sql::ORDER);
        $sql->ifThenAppend(!$comma,Sql::BY);
        $sql->ifThenAppend($comma,',',[],false);
        $sql->append($column);
        $sql->append(strtoupper($sort));

        return $sql;
    }
}
