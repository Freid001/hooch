<?php


namespace QueryMule\Query\Builder\Clause;

use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;

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
        $sql .= Sql::FROM . Sql::SQL_SPACE . $table->getName();
        $sql .= !empty($alias) ? Sql::SQL_SPACE.Sql:: AS.Sql::SQL_SPACE.$alias : null;

        return new Sql($sql);
    }
}
