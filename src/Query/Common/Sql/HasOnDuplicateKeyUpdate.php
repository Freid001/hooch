<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\InsertInterface;

/**
 * Trait HasOnDuplicateKeyUpdate
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOnDuplicateKeyUpdate
{
    /**
     * @param array $values
     * @return InsertInterface
     * @throws SqlException
     */
    public function onDuplicateKeyUpdate(array $values): InsertInterface
    {
        if($this instanceof InsertInterface) {
            $sql = $this->query()->sql();
            $sql->append(Sql::ON);
            $sql->append(Sql::DUPLICATE);
            $sql->append(Sql::KEY);
            $sql->append(Sql::UPDATE);

            $query = $this->query();
            $sql->append(implode(",",
                array_map(function ($column) use ($query, $sql) {
                    return $query->accent()->append($column) . Sql::SQL_SPACE . Sql::SQL_EQUAL . Sql::SQL_QUESTION_MARK;
                }, array_keys($values))
            ), array_values($values));

            $this->query()->append(Sql::INSERT, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke InsertInterface in: %s.", get_class($this)));
        }
    }
}