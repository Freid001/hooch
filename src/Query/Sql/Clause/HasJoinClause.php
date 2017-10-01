<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasJoinClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasJoinClause
{
    /**
     * @param $column
     * @param bool $alias
     * @param bool $as
     * @param bool $comma
     * @return Sql
     */
    private function joinClause($column, $alias = false, $as = false, $comma = false)
    {
        $sql = '';
        $sql .= !empty($comma) ? ',' : '';
        $sql .= !empty($alias) ? $alias.'.' : '';
        $sql .= $column;
        $sql .= !empty($as) ? ' '.SelectInterface::COL_AS.' '.$as : '';

        return new Sql($sql);
    }
}