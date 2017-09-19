<?php

namespace QueryMule\Builder\Sql\MySql;

use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasColumnClause;
use QueryMule\Query\Sql\Clause\HasFromClause;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\MySql
 */
class Select implements SelectInterface
{
    use Accent;
    use Query;

    use HasFromClause;
    use HasColumnClause;


    private $cols;
    private $from;

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

        $this->setAccent('`');
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

            //Increment position only when we have not passed a key and we are using the default int position
            if((int)$key !== $i){
                $i++;
            }

            //Generate column
            $sql = $this->columnClause(
                $this->addAccent($col),
                $alias,
                ($key !== $i) ? $key : null,
                empty($this->queryGet(self::COLS))
            );

            //Add sql to query
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

    public function where($column, $operator = null, $value = null, $clause = self::WHERE) : SelectInterface
    {







        return $this;
    }


//    public function orWhere() : SelectInterface
//    {
//        return $this;
//    }













    public function build()
    {

        $this->queryBuild([
            self::SELECT,
            self::COLS,
            self::INTO,
            self::VALUES,
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