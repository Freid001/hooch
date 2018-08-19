<?php


namespace QueryMule\Query\Builder\Clause;

use QueryMule\Query\Sql\Sql;

/**
 * Class HasHavingClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasHavingClause
{
    /**
     * @param string $column
     * @param string|null $operator
     * @param string|null $value
     * @return Sql
     */
    final protected function havingClause($column,$operator = null,$value = null)
    {
        $sql = '';
        $sql .= Sql::HAVING.' '.$column.' '.$operator;

        return new Sql($sql,$value);
    }
}
