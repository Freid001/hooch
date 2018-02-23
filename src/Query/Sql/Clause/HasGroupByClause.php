<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasGroupByClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasGroupByClause
{
    /**
     * @param $column
     * @param bool $withClause
     * @return Sql
     */
    final protected function groupByClause($column, $withClause = true)
    {
        $sql = '';
        if($withClause) {
            $sql .= SelectInterface::GROUP.' '.$column;
        }else {
            $sql .= ','.$column;
        }

        return new Sql($sql);
    }
}