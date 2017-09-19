<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class HasFromClause
 * @package QueryMule\Query\Sql\MySql\Clause
 */
trait HasFromClause
{
    /**
     * @param TableInterface $table
     * @param null $alias
     * @return string
     */
    private function fromClause(TableInterface $table, $alias = null)
    {
        $sql = '';
        $sql .= SelectInterface::FROM.' '.$table->getTableName();
        return $sql;
    }
}