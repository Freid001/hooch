<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasOrderBy
 * @package QueryMule\Query\Common\Sql
 */
trait HasOrderBy
{
    /**
     * @param $column
     * @param string|null $order
     * @return SelectInterface
     * @throws SqlException
     */
    public function orderBy($column, ?string $order = SQL::DESC): SelectInterface
    {
        if($this instanceof SelectInterface){
            $sql = $this->query()->sql();

            $sql->ifThenAppend(!$this->query()->hasClause(Sql::ORDER),Sql::ORDER)
                ->ifThenAppend(!$this->query()->hasClause(Sql::ORDER),Sql::BY)
                ->ifThenAppend($this->query()->hasClause(Sql::ORDER), ',' , [], false)
                ->append($this->query()->accent()->append($column, '.'))
                ->append(strtoupper($order));

            $this->query()->append(Sql::ORDER, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
