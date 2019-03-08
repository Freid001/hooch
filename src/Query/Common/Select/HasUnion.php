<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

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
     * @throws InterfaceException
     */
    public function union(Sql $unionSql, bool $all = false): SelectInterface
    {
        if($this instanceof SelectInterface){
            $this->query()->clause(Sql::UNION, function (Sql $sql) use ($unionSql, $all) {
                return $sql
                    ->append(Sql::UNION)
                    ->ifThenAppend(!empty($all), Sql::ALL)
                    ->append($unionSql);
            });

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
