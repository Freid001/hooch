<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasOn
 * @package Redstraw\Hooch\Query\Common\Sql
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
