<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasUnionClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasUnionClause
{
    /**
     * @param SelectInterface $select
     * @param bool $all
     * @return Sql
     */
    final protected function unionClause(SelectInterface $select,$all = false)
    {
        $query = $select->build();

        $sql = '';
        $sql .= SelectInterface::UNION;
        $sql .= !empty($all) ? ' '.SelectInterface::ALL.' ' : ' ';
        $sql .= $query->sql();

        return new Sql($sql,$query->parameters());
    }
}
