<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Operator\OperatorInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasHaving
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasHaving
{
    /**
     * @param string $column
     * @param OperatorInterface $operator
     * @return SelectInterface
     * @throws SqlException
     */
    public function having(string $column, OperatorInterface $operator): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $column = $this->query()->accent()->append($column, '.');

            $this->query()->sql()
                ->append(Sql::HAVING)
                ->append($column)
                ->append($operator->build());

            $this->query()->toClause(Sql::HAVING);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke FilterInterface in: %s.", get_class($this)));
        }
    }
}
