<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasHaving
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasHaving
{
    /**
     * @param $column
     * @param OperatorInterface $operator
     * @return SelectInterface
     * @throws SqlException
     */
    public function having($column, OperatorInterface $operator): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $column = $this->query()->accent()->append($column, '.');

            $sql = $this->query()->sql();
            $sql->append(Sql::HAVING);
            $sql->append($column);
            $sql->append($operator);

            $this->query()->append(Sql::HAVING, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
