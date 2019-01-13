<?php

declare(strict_types=1);

namespace QueryMule\Query\Common\Sql;


use QueryMule\Query\Exception\SqlException;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasGroupBy
 * @package QueryMule\Query\Common\Sql
 */
trait HasGroupBy
{
    /**
     * @param $column
     * @param string|null $alias
     * @return SelectInterface
     * @throws SqlException
     */
    public function groupBy($column, ?string $alias = null): SelectInterface
    {
        if($this instanceof SelectInterface) {
            $sql = $this->query()->sql();

            $sql->ifThenAppend(empty($this->query()->hasClause(Sql::GROUP)), Sql::GROUP)
                ->ifThenAppend(!empty($this->query()->hasClause(Sql::GROUP)), ',', [], false)
                ->ifThenAppend(!is_null($alias), $this->query()->accent()->append($alias) . '.', [], false)
                ->append($this->query()->accent()->append($column));

            $this->query()->append(Sql::GROUP, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
