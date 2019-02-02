<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Operator\OperatorInterface;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;
use QueryMule\Query\Sql\Statement\OnFilterInterface;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasHaving
 * @package QueryMule\Query\Common\Sql
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
