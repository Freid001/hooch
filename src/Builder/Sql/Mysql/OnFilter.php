<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
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
     * @param OperatorInterface $operator
     * @return $this|OnFilterInterface
     */
    public function on($column, OperatorInterface $operator)
    {
        $this->on = true;

        $sql = new Sql();
        $sql->append(Sql::ON);
        $sql->append($column);
        $sql->append($operator);

        $this->query()->add(Sql::JOIN, $sql);

        return $this;
    }

    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return $this|OnFilterInterface
     */
    public function orOn($column, OperatorInterface $operator)
    {
//        $this->orWhere($column,$comparison,null);

        return $this;
    }
}