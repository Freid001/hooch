<?php


namespace QueryMule\Query\Builder\Clause;

use QueryMule\Query\Sql\Sql;

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
    final protected function offsetClause(int $offset)
    {
        return new Sql(Sql::OFFSET.' '.$offset);
    }
}
