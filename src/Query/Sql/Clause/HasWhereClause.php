<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Nested;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

/**
 * Class HasWhereClause
 * @package QueryMule\Query\Sql\Clause
 */
trait HasWhereClause
{
    use Nested;

    /**
     * @param null|string $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Sql
     */
    final protected function whereClause(?string $column, ?Comparison $comparison = null, ?Logical $logical = null)
    {
        $value = [];
        if (!empty($comparison)) {
            $value = array_merge($value, $comparison->build()->parameters());
        }

        if (!empty($logical)) {
            $value = array_merge($value, $logical->build()->parameters());
        }

        $sql = "";
        if(!is_null($column) ||
            !($logical->getOperator() == Sql:: AND || $logical->getOperator() == Sql:: OR)){
            $sql .= Sql::WHERE . Sql::SQL_SPACE;
            $sql .= $this->nested(true);
        }

        $sql .= !is_null($column) ? $column . Sql::SQL_SPACE : "";
        $sql .= !is_null($comparison) ? $comparison->build()->sql() : "";
        $sql .= !is_null($logical) ? $logical->build()->sql() : "";

        return new Sql($sql, $value);
    }

    /**
     * @param \Closure $column
     * @param null|Logical $logical
     * @return Sql
     */
    final protected function nestedWhereClause(\Closure $column, ?Logical $logical)
    {
        $this->setNested(true);

        if($logical){
           $logical->setNested(true);
        }

        $column($this);

        return new Sql($this->setNested(true)->nested(false));
    }
}
