<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\InsertInterface;

/**
 * Trait HasValues
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasValues
{
    /**
     * @param array $values
     * @return InsertInterface
     * @throws SqlException
     */
    public function values(array $values): InsertInterface
    {
        if($this instanceof InsertInterface) {
            $sql = $this->query()->sql();
            $sql->ifThenAppend(empty($this->query()->hasClause(Sql::VALUES)),Sql::VALUES);
            $sql->ifThenAppend(!empty($this->query()->hasClause(Sql::VALUES)),",");
            $sql->append(Sql::SQL_BRACKET_OPEN);
            $sql->append(implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)), array_values($values));
            $sql->append(Sql::SQL_BRACKET_CLOSE);

            $this->query()->append(Sql::VALUES, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke InsertInterface in: %s.", get_class($this)));
        }
    }
}
