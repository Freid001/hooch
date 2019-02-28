<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasOrderBy
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasOrderBy
{
    /**
     * @param string $column
     * @param string|null $order
     * @return SelectInterface
     * @throws InterfaceException
     */
    public function orderBy(string $column, ?string $order = SQL::DESC): SelectInterface
    {
        if($this instanceof SelectInterface){
            $this->query()->sql()
                ->ifThenAppend(!$this->query()->hasClause(Sql::ORDER),Sql::ORDER)
                ->ifThenAppend(!$this->query()->hasClause(Sql::ORDER),Sql::BY)
                ->ifThenAppend($this->query()->hasClause(Sql::ORDER), ',' , [], false)
                ->append($this->query()->accent()->append($column, '.'))
                ->append(strtoupper($order));

            $this->query()->appendSqlToClause(Sql::ORDER);

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
