<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasOffset
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOffset
{
    /**
     * @param int $offset
     * @return SelectInterface
     * @throws InterfaceException
     */
    public function offset(int $offset): SelectInterface
    {
        if($this instanceof SelectInterface){
            $this->query()->sql()
                ->append(Sql::OFFSET)
                ->append($offset);

            $this->query()->appendSqlToClause(Sql::OFFSET);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
