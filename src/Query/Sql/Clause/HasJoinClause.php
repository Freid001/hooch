<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Builder\Exception\SqlException;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class HasJoinClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasJoinClause
{
    /**
     * @param $type
     * @param RepositoryInterface $table
     * @param null $alias
     * @return Sql
     * @throws SqlException
     */
    private function joinClause($type, RepositoryInterface $table, $alias = null)
    {
        //->leftJoin('posts', 'users.id', '=', 'posts.user_id')
//        ->join('contacts', function ($join) {
//        $join->on('users.id', '=', 'contacts.user_id')->orOn(...);
//    })

        //LEFT JOIN posts ON user.id = post.user_id;
        //RIGHT JOIN posts ON user.id = post.user_id;
        //INNER JOIN Customers ON Orders.CustomerID=Customers.CustomerID;
        //FULL OUTER JOIN table2 ON table1.column_name = table2.column_name;

        $sql = '';
        switch ($type) {
            case FilterInterface::LEFT_JOIN:
                $sql .= FilterInterface::LEFT_JOIN . ' ' . $table->getName();
                break;

            default:
                throw new SqlException('Join type not supported.');
        }

        $sql .= !empty($alias) ? ' '.SelectInterface::COL_AS.' '.$alias : ' ';

        return new Sql($sql);
    }

    /**
     * @param $first
     * @param null $operator
     * @param null $second
     * @return Sql
     */
    private function onClause($first, $operator = null, $second = null, $clause = FilterInterface::ON)
    {
        $sql = '';
        $sql .= $clause;
        $sql .= ' '.$first;
        $sql .= ' '.$operator;
        $sql .= ' '.$second;

        return new Sql($sql);
    }
}






