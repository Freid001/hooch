<?php

namespace Redstraw\Hooch\Builder\Sql\Pgsql;

use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Class Select
 * @package Redstraw\Hooch\Builder\Sql\Pgsql
 */
abstract class Select
{
    /**
     * Select constructor.
     * @param array $cols
     * @param RepositoryInterface|null $table
     */
    public function __construct(array $cols = [], RepositoryInterface $table = null)
    {
        //parent::__construct($cols, $table, "'");
    }

    /**
     * @param array $cols
     * @return SelectInterface
     */
    final public function cols($cols = [Sql::SQL_STAR]) : SelectInterface
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
