<?php


namespace QueryMule\Query\Sql\Clause;

use QueryMule\Query\Sql\Nested;
use QueryMule\Query\Sql\Sql;
use QueryMule\Sql\Operator\Comparison;

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
     * @param null $value
     * @param string $operator
     * @return Sql
     */
    final protected function whereClause(?string $column, ?Comparison $comparison = null, $value = null, string $operator = Sql::WHERE)
    {
        if(!empty($comparison)){
            $comparison = $comparison->build();
        }

        if($value instanceof Sql) {
            $comparison = $value->sql();
            $value = $value->parameters();
        }

        $sql = null;
        switch ($operator) {
            case Sql::WHERE:
                $sql .= Sql::WHERE.Sql::SQL_SPACE;
                $sql .= $this->nest(true);
                $sql .= implode(Sql::SQL_SPACE, [
                    $column,
                    $comparison
                ]);
                break;

            case Sql::AND:
                $sql .= Sql::AND.Sql::SQL_SPACE;
                $sql .= $this->nest(true);
                $sql .= implode(Sql::SQL_SPACE, [
                    $column,
                    $comparison
                ]);
                break;

            case Sql::OR:
                $sql .= Sql::OR.Sql::SQL_SPACE;
                $sql .= $this->nest(true);
                $sql .= implode(Sql::SQL_SPACE, [
                    $column,
                    $comparison
                ]);
                break;

            case Sql::NOT:
//                $sql .= FilterInterface::NOT.SelectInterface::SQL_SPACE;
//                $sql .= $this->nestedBracket(true);
//                $sql .= implode(SelectInterface::SQL_SPACE, [
//                    $column,
//                    $comparison
//                ]);
                break;

            case 'SOME':
                break;

            case 'ANY':
                break;

            case 'ALL':
                break;

            case Sql::IN:
                $sql .= Sql::IN.Sql::SQL_SPACE;
                $sql .= implode(Sql::SQL_SPACE, [
                    Sql::SQL_BRACKET_OPEN,
                    implode( ",", array_fill(0, count($value), "?")),
                    Sql::SQL_BRACKET_CLOSE
                ]);
                break;

//WHERE     [DONE]
//ALL       ...
//AND       [DONE]
//ANY       ...
//BETWEEN
//EXISTS
//IN	    [DONE]
//LIKE      [DONE]
//NOT       ...
//OR	    [DONE]
//SOME      ...
        }

        return new Sql($sql, !is_array($value) ? [$value] : $value);
    }

    /**
     * @param \Closure $column
     * @return Sql
     */
    final protected function nestedWhereClause(\Closure $column)
    {
        $this->nested = true;
        $column($this);
        $this->nested = true;
        return new Sql($this->nest(false));
    }

}
