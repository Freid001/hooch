<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasColsClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasColumnClause
{
    /**
     * @param $column
     * @param bool $alias
     * @param bool $as
     * @param bool $comma
     * @return Sql
     */
    private function columnClause($column, $alias = false, $as = false, $comma = false)
    {
        $sql = '';
        $sql .= !empty($comma) ? $comma.' ' : '';
        $sql .= !empty($alias) ? $alias.'.' : '';
        $sql .= $column;
        $sql .= !empty($as) ? ' '.SelectInterface::COL_AS.' '.$as : '';

        return new Sql($sql);
    }
}