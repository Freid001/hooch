<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasOrderByClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasOrderByClause
{
    /**
     * @param string $column
     * @param string $sort
     * @param bool $alias
     * @param bool $comma
     * @return Sql
     */
    final protected function orderByClause($column, $sort = 'desc', $alias = false, $comma = false)
    {
        $sql = '';

        if($comma) {
            $sql .= ',';
            $sql .= !empty($alias) ? $alias.'.'.$column : $column;
            $sql .= ' '.strtoupper($sort);
        }else {
            $sql = SelectInterface::ORDER;
            $sql .= !empty($alias) ? ' '.$alias.'.'.$column : ' '.$column;
            $sql .= ' '.strtoupper($sort);
        }

        return new Sql($sql);
    }
}
