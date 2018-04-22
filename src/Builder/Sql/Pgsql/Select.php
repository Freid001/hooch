<?php

namespace QueryMule\Builder\Sql\Pgsql;

use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Builder\Sql\Generic\Select as GenericSelect;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Pgsql
 */
class Select extends GenericSelect
{
    /**
     * Select constructor.
     * @param array $cols
     * @param RepositoryInterface|null $table
     */
    public function __construct(array $cols = [], RepositoryInterface $table = null)
    {
        parent::__construct($cols, $table, "'");
    }

    /**
     * @param array $cols
     * @param null $alias
     * @return SelectInterface
     */
    final public function cols($cols = [Sql::SQL_STAR], $alias = null) : SelectInterface
    {
        $i = 0;
        foreach($cols as $key => &$col){
            if((int)$key !== $i){
                $i++; // increment only when we using int positions
            }

            $col = !empty($alias) ? $alias.'.'.$col : $col; // append alias before adding accents

            $sql = $this->columnClause(
                ($col !== Sql::SQL_STAR) ? $this->addAccent($col,'.') : $col,
                false,
                ($key !== $i) ? $key : null,
                !empty($this->queryGet(Sql::COLS))
            );

            $this->queryAdd(Sql::COLS,$sql);
        }

        return $this;
    }
}
