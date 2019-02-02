<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasOn
 * @package QueryMule\Query\Common\Sql
 */
trait HasOn
{
    private $on = false;

    /**
     * @param $column
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     * @throws SqlException
     */
    public function on($column, ?OperatorInterface $operator): OnFilterInterface
    {
        if ($this instanceof OnFilterInterface) {
            $sql = $this->query()->sql();

            if ($column instanceof \Closure) {
                call_user_func($column, $filter = $this);
            } else {
                $sql->ifThenAppend(!$this->on, Sql::ON);
                $sql->ifThenAppend($this->on, Sql::AND);
                $sql->ifThenAppend(!is_null($column), $column);

                $this->on = true;
            }

            $this->query()->append(Sql::JOIN, $sql->append($operator));

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke OnFilterInterface in: %s.", get_class($this)));
        }
    }
}
