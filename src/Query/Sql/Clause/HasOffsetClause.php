<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasOffsetClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasOffsetClause
{
    /**
     * @param int $offset
     * @return Sql
     */
    final protected function offsetClause($offset)
    {
        $sql = '';
        $sql .= Sql::OFFSET.' '.$offset;
        return new Sql($sql);
    }
}
