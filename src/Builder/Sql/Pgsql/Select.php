<?php

namespace QueryMule\Builder\Sql\Pgsql;

use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasColumnClause;
use QueryMule\Query\Sql\Clause\HasFromClause;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Pgsql
 */
class Select implements SelectInterface
{
    use Accent;
    use Query;

    use HasFromClause;
    use HasColumnClause;
    use HasWhereClause;

    /**
     * @param array $cols
     * @param TableInterface|null $table
     */
    public function __construct(array $cols = [], TableInterface $table = null)
    {
        if(!empty($cols)) {
            $this->cols($cols);
        }

        if(!empty($table)) {
            $this->from($table);
        }

        $this->setAccent("'");
        $this->queryAdd(self::SELECT,new Sql(self::SELECT));
    }

    /**
     * @param bool $ignore
     * @return SelectInterface
     */
    public function ignoreAccent($ignore = true) : SelectInterface
    {
        $this->ignoreAccentSymbol($ignore);
        return $this;
    }

    /**
     * @param array $cols
     * @param null $alias
     * @return SelectInterface
     */
    public function cols($cols = [self::SQL_STAR], $alias = null) : SelectInterface
    {
        $i = 0;
        foreach($cols as $key => &$col){
            if((int)$key !== $i){
                $i++; // increment only when we using int positions
            }

            $col = !empty($alias) ? $alias.'.'.$col : $col; // append alias before adding accents

            $sql = $this->columnClause(
                ($col !== self::SQL_STAR) ? $this->addAccent($col) : $col,
                false,
                ($key !== $i) ? $key : null,
                !empty($this->queryGet(self::COLS))
            );

            $this->queryAdd(self::COLS,$sql);
        }

        return $this;
    }

    /**
     * @param TableInterface $table
     * @param null $alias
     * @return SelectInterface
     */
    public function from(TableInterface $table, $alias = null) : SelectInterface
    {
        $this->queryAdd(self::FROM,$this->fromClause($table,$alias));

        return $this;
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @return SelectInterface
     */
    public function where($column, $operator = null, $value = null) : SelectInterface
    {
        $clause = empty($this->queryGet(self::WHERE)) ? self::WHERE : self::AND_WHERE;

        $this->queryAdd(self::WHERE,$this->whereClause($column,$operator,$value,$clause));

        return $this;
    }

    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @return SelectInterface
     */
    public function orWhere($column, $operator = null, $value = null) : SelectInterface
    {
        $this->queryAdd(self::WHERE,$this->whereClause($column,$operator,$value,self::OR_WHERE));

        return $this;
    }

    /**
     * @return \QueryMule\Query\Sql\Sql
     */
    public function build() : Sql
    {
        return $this->queryBuild([
            self::SELECT,
            self::COLS,
            self::FROM,
            self::JOIN,
            self::WHERE,
            self::GROUP,
            self::ORDER,
            self::HAVING,
            self::LIMIT
        ]);
    }
}