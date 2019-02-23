<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

/**
 * Trait HasOrOn
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrOn
{
    private $on = false;

    /**
     * @param $column
     * @param OperatorInterface|null $operator
     * @return OnFilterInterface
     * @throws SqlException
     */
    public function orOn($column, ?OperatorInterface $operator): OnFilterInterface
    {
        if ($this instanceof OnFilterInterface) {
            $sql = $this->query()->sql();

            if (is_callable($column)) {
                $this->on($this->query()->accent()->append($column,'.'), $operator);
            }else {
                $sql->ifThenAppend(!is_null($column),Sql::OR)
                    ->ifThenAppend(!is_null($column),$this->query()->accent()->append($column,'.'))
                    ->append($operator->build());
            }

            $this->query()->appendSqlToClause(Sql::JOIN);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke OnFilterInterface in: %s.", get_class($this)));
        }
    }
}
