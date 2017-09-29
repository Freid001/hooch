<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Table\TableInterface;

/**
 * Interface FilterInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface FilterInterface
{
    /**
     * @param $column
     * @param null $operator
     * @param null $value
     * @return SelectInterface
     */
    public function where($column, $operator = null, $value = null) : SelectInterface;

     /**
      * @param $column
      * @param null $operator
      * @param null $value
      * @return SelectInterface
      */
    public function orWhere($column, $operator = null, $value = null) : SelectInterface;

    /**
     * @return Sql
     */
    public function build() : Sql;
}