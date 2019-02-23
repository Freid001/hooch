<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasUnion
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasUnion
{
    /**
     * @param Sql $unionSql
     * @param bool $all
     * @return SelectInterface
     * @throws SqlException
     */
    public function union(Sql $unionSql, bool $all = false): SelectInterface
    {
        if($this instanceof SelectInterface){
            $this->query()->sql()
                ->append(Sql::UNION)
                ->ifThenAppend(!empty($all), Sql::ALL)
                ->append($unionSql);

            $this->query()->appendSqlToClause(Sql::UNION);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
