<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Insert;


use Redstraw\Hooch\Query\Exception\InterfaceException;
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
     * @throws InterfaceException
     */
    public function values(array $values): InsertInterface
    {
        if($this instanceof InsertInterface) {
            $this->query()->sql()
                ->ifThenAppend(empty($this->query()->hasClause(Sql::VALUES)),Sql::VALUES)
                ->ifThenAppend(!empty($this->query()->hasClause(Sql::VALUES)),",")
                ->append(Sql::SQL_BRACKET_OPEN)
                ->append(implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)), array_values($values))
                ->append(Sql::SQL_BRACKET_CLOSE);

            $this->query()->appendSqlToClause(Sql::VALUES);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke InsertInterface in: %s.", get_class($this)));
        }
    }
}
