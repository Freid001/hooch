<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasFromClause
 * @package QueryMule\Query\Sql\MySql\Clause
 */
trait HasFromClause
{
    /**
     * @param RepositoryInterface $table
     * @param null $alias
     * @return Sql
     */
    final protected function fromClause(RepositoryInterface $table, $alias = null)
    {
        $sql = '';
        $sql .= SelectInterface::FROM.' '.$table->getName();
        $sql .= !empty($alias) ? ' '.SelectInterface::AS.' '.$alias : null;

        return new Sql($sql);
    }
}
