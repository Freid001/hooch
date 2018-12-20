<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Class Join
 * @package QueryMule\Builder\Sql\Mysql
 */
class OnFilter extends Filter implements OnFilterInterface
{
    private $on = false;

    /**
     * @param $column
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface|FilterInterface
     */
    public function on($column, ?OperatorInterface $operator): OnFilterInterface
    {
        $this->on = true;

        $sql = new Sql();

        if ($column instanceof \Closure) {
            call_user_func($column, $query = $this);
        }else {
            $sql->ifThenAppend(!is_null($column),Sql::ON);
            $sql->ifThenAppend(!is_null($column),$column);
        }

        $sql->append($operator);

        $this->query()->append(Sql::JOIN, $sql);

        return $this;
    }

    /**
     * @param $column
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface|FilterInterface
     */
    public function orOn($column, ?OperatorInterface $operator): OnFilterInterface
    {
//        $this->orWhere($column,$comparison,null);

        return $this;
    }
}