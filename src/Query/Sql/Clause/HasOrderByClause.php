<?php


namespace QueryMule\Query\Builder\Clause;

use QueryMule\Query\Sql\Sql;

/**
 * Class HasOrderByClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasOrderByClause
{
    /**
     * @param string $column
     * @param string $sort
     * @param bool $comma
     * @return Sql
     */
    final protected function orderByClause($column, $sort = SQL::DESC, $comma = false)
    {
        $sql = '';

        if($comma) {
            $sql .= ',';
            $sql .= !empty($alias) ? $alias.'.'.$column : $column;
            $sql .= ' '.strtoupper($sort);
        }else {
            $sql = Sql::ORDER;
            $sql .= Sql::SQL_SPACE;
            $sql .= Sql::BY;
            $sql .= !empty($alias) ? ' '.$alias.'.'.$column : ' '.$column;
            $sql .= ' '.strtoupper($sort);
        }

        return new Sql($sql);
    }
}
