<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;

/**
 * Class HasColsClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasColumnClause
{
    /**
     * @param string $column
     * @param bool $alias
     * @param bool $as
     * @param bool $comma
     * @return Sql
     */
    final protected function columnClause($column, $alias = false, $as = false, $comma = false)
    {
        $sql = null;
        $sql .= !empty($comma) ? ',' : '';
        $sql .= !empty($alias) ? $alias.'.' : '';
        $sql .= $column;
        $sql .= !empty($as) ? Sql::SQL_SPACE.Sql::AS.Sql::SQL_SPACE.$as : '';

        return new Sql($sql);
    }
}
