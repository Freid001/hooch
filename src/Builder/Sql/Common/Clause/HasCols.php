<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Builder\Sql\Mysql\Filter;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasCols
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasCols
{
    use Common;

    /**
     * @param array $cols
     * @param null $alias
     * @return $this
     */
    public function cols($cols = [Sql::SQL_STAR], $alias = null)
    {
        $i = 0;
        foreach ($cols as $key => &$col) {
            if ((int)$key !== $i) {
                $i++; //Increment only when using int positions
            }

            $sql = $this->columnClause(
                ($col !== Sql::SQL_STAR) ? $this->accent()->append($col) : $col,
                !empty($alias) ? $this->accent()->append($alias) : $alias,
                ($key !== $i) ? $key : null,
                !empty($this->query()->get(Sql::COLS))
            );

            $this->query()->add(Sql::COLS, $sql);
        }

        return $this;
    }

    /**
     * @param $column
     * @param bool $alias
     * @param bool $as
     * @param bool $comma
     * @return Sql
     */
    private function columnClause($column, $alias = false, $as = false, $comma = false): Sql
    {
        $sql = new Sql();
        $sql->appendIf($comma,',',[],false);
        $sql->appendIf($alias,$alias.'.',[],false);
        $sql->append($column);
        $sql->appendIf($as,Sql::AS . Sql::SQL_SPACE . $as);

        return $sql;
    }
}
