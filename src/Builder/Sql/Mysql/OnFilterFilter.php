<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Class Join
 * @package QueryMule\Builder\Sql\Mysql
 */
class OnFilterFilter extends Filter implements OnFilterInterface
{
    private $on = false;

    /**
     * @param $column
     * @param Comparison|null $comparison
     * @param Logical|null $logical
     * @return $this|OnFilterInterface
     */
    public function on($column, ?Comparison $comparison, ?Logical $logical = null)
    {
        $this->on = true;

        $sql = Sql::ON . Sql::SQL_SPACE;
        $sql .= $column . Sql::SQL_SPACE;
        $sql .= $comparison->build()->sql();

        $this->query()->add(Sql::JOIN,new Sql($sql,$comparison->build()->parameters()));

        return $this;
    }

    /**
     * @param $column
     * @param Comparison|null $comparison
     * @param Logical|null $logical
     * @return $this|mixed
     */
    public function orOn($column, ?Comparison $comparison, ?Logical $logical = null)
    {
        $this->orWhere($column,$comparison,null);

        return $this;
    }
}