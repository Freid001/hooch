<?php


namespace QueryMule\Query\Builder\Clause;

use QueryMule\Query\Sql\Sql;

/**
 * Class HasGroupByClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasGroupByClause
{
    /**
     * @param string $column
     * @param bool $alias
     * @param bool $comma
     * @return Sql
     */
    final protected function groupByClause($column, $alias = false, $comma = false)
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