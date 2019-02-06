<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasLimit
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasLimit
{
    /**
     * @param int $limit
     * @return SelectInterface
     * @throws SqlException
     */
    public function limit(int $limit): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $sql = $this->query()->sql();

            $sql->append(Sql::LIMIT)
                ->append($limit);

            $this->query()->append(Sql::LIMIT, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
