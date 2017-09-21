<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasWhereClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasWhereClause
{
    /**
     * @param $column
     * @param $operator
     * @param $value
     * @param $clause
     * @return Sql
     * @throws SqlException
     */
    private function whereClause($column,$operator,$value,$clause = SelectInterface::WHERE)
    {
        $sql = '';
        switch ($clause){
            case SelectInterface::WHERE:
                $sql .= SelectInterface::WHERE.' '.$column.' '.$operator.' ?';
                break;

            case SelectInterface::AND_WHERE:
                $sql .= SelectInterface::AND_WHERE.' '.$column.' '.$operator.' ?';
                break;

            case SelectInterface::OR_WHERE:
                $sql .= SelectInterface::OR_WHERE.' '.$column.' '.$operator.' ?';
                break;

            default:
                throw new SqlException($clause.' not allowed.');
                break;
        }

        return new Sql($sql,[$value]);
    }
}