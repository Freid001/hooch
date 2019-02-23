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
            $this->query()->sql()
                ->append(Sql::ON)
                ->append(Sql::DUPLICATE)
                ->append(Sql::KEY)
                ->append(Sql::UPDATE);

            $query = $this->query();
            $this->query()->sql()->append(implode(",",
                array_map(function ($column) use ($query) {
                    return $query->accent()->append($column) . Sql::SQL_SPACE . Sql::SQL_EQUAL . Sql::SQL_QUESTION_MARK;
                }, array_keys($values))
            ), array_values($values));

            $this->query()->appendSqlToClause(Sql::UPDATE);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke InsertInterface in: %s.", get_class($this)));
        }
    }
}
