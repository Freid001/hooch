<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class HasWhereClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasWhereClause
{
    /**
     * @var bool
     */
    private $ignoreWhereClause = false;

    /**
     * @param $column
     * @param $operator
     * @param $value
     * @param $clause
     * @return Sql
     * @throws SqlException
     */
    private function whereClause($column,$operator = null,$value = null,$clause = FilterInterface::WHERE)
    {
        if($this->ignoreWhereClause){
            $clause = null;
        }

        $sql = '';
        switch ($clause){
            case FilterInterface::WHERE:
                $sql .= FilterInterface::WHERE.' '.$column.' '.$operator;
                break;

            case FilterInterface::AND:
                $sql .= FilterInterface::AND.' '.$column.' '.$operator;
                break;

            case FilterInterface::OR:
                $sql .= FilterInterface::OR.' '.$column.' '.$operator;
                break;

            default:
                $sql .= $column.' '.$operator;
                break;
        }

        return new Sql($sql,[$value]);
    }

    /**
     * @param \Closure $column
     */
    private function nestedWhereClause(\Closure $column)
    {
        $this->ignoreWhereClause = true;
        $column($this);
        $this->ignoreWhereClause = false;
    }
}