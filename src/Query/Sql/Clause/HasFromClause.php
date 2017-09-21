<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;
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
     * @return Sql
     */
    private function fromClause(TableInterface $table, $alias = null)
    {
        $sql = '';
        $sql .= SelectInterface::FROM.' '.$table->getTableName();
        $sql .= !empty($alias) ? ' '.SelectInterface::COL_AS.' '.$alias : null;

        return new Sql($sql);
    }
}