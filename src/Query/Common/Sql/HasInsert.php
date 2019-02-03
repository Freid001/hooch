<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\InsertInterface;

/**
 * Trait HasInsert
 * @package QueryMule\Query\Common\Sql
 */
trait HasInsert
{
    /**
     * @param array $values
     * @return InsertInterface
     * @throws SqlException
     */
    public function insert(array $values): InsertInterface
    {
        if($this instanceof InsertInterface) {
            $sql = $this->query()->sql();
            $sql->append(Sql::SQL_BRACKET_OPEN);
            
            $query = $this->query();
            $sql->append(implode(",",
                array_map(function ($column) use ($query){
                    return $query->accent()->append($column);
                }, array_keys($values))
            ));

            $sql->append(Sql::SQL_BRACKET_CLOSE);
            $sql->append(Sql::VALUES);
            $sql->append(Sql::SQL_BRACKET_OPEN);
            $sql->append(implode(",", array_fill(0, count($values), Sql::SQL_QUESTION_MARK)), array_values($values));
            $sql->append(Sql::SQL_BRACKET_CLOSE);

            $this->query()->append(Sql::INSERT, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke InsertInterface in: %s.", get_class($this)));
        }
    }
}