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
                $column->call($this);
            } else {
                $sql->ifThenAppend(!$this->on, Sql::ON)
                    ->ifThenAppend($this->on, Sql::AND)
                    ->ifThenAppend(!is_null($column), $this->query()->accent()->append($column,'.'))
                    ->append($operator->build());

                $this->on = true;
            }

            $this->query()->toClause(Sql::JOIN);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke OnFilterInterface in: %s.", get_class($this)));
        }
    }
}
