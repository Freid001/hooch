<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\SelectInterface;
use QueryMule\Query\Table\TableInterface;

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
     * @return string
     */
    private function columnClause($column, $alias = false, $as = false, $comma = false)
    {
        $sql = '';
        $sql .= !empty($comma) ? $comma.' ' : '';
        $sql .= !empty($alias) ? $alias.'.' : '';
        $sql .= $column;
        $sql .= !empty($as) ? ' '.self::COL_AS.' '.$as : '';

        return $sql;
    }
}