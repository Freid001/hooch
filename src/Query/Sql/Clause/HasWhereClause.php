<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;


trait HasWhereClause
{

    private function whereClause()
    {

        switch ($clause){
            case SelectInterface::WHERE:
                break;
            default:
                break;
        }

        $sql = '';
        $sql .= SelectInterface::WHERE.' ';
        return $sql;
    }
}