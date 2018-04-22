<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasLimitClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasLimitClause
{
    /**
     * @param int $limit
     * @return Sql
     */
    final protected function limitClause($limit)
    {
        $sql = '';
        $sql .= Sql::LIMIT.' '.$limit;

        return new Sql($sql);
    }
}
