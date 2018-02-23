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
     * @param array $column
     * @return Sql
     */
    final protected function orderByClause($column, $sort, $withClause = true)
    {
        $sql = '';
        $sql .= SelectInterface::ORDER.' '.$column;

        return new Sql($sql);
    }
}
