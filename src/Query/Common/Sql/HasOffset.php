<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasOffset
 * @package QueryMule\Query\Common\Sql
 */
trait HasOffset
{
    /**
     * @param int $offset
     * @return SelectInterface
     * @throws SqlException
     */
    public function offset(int $offset): SelectInterface
    {
        if($this instanceof SelectInterface){
            $sql = $this->query()->sql();

            $sql->append(Sql::OFFSET)
                ->append($offset);

            $this->query()->append(Sql::OFFSET, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
