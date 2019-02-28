<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasLimit
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasLimit
{
    /**
     * @param int $limit
     * @return SelectInterface
     * @throws InterfaceException
     */
    public function limit(int $limit): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $this->query()->sql()
                ->append(Sql::LIMIT)
                ->append($limit);

            $this->query()->appendSqlToClause(Sql::LIMIT);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
