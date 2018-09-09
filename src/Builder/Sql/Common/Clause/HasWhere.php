<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasWhere
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasWhere
{
    use Common;

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return $this
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null)
    {
        $column = $this->accent()->append($column,'.');
        $logical = is_null($logical) ? new Logical() : $logical;

        if (!empty($this->query()->get(Sql::WHERE))) {
            if (empty($logical->getOperator()) || $logical->getOperator() != Sql::OR) {
                if ($this->logical()->getNested()) {
                    $logical->setNested(true)->and($column, $comparison, $logical);
                    $this->logical()->setNested(false);
                } else {
                    $logical->and($column, $comparison, $logical);
                }
            }
        }

        $this->query()->add(Sql::WHERE, $this->whereClause(
            !in_array($logical->getOperator(), [Sql::AND,Sql::OR]) ? $column : null,
            !$logical->getOperator() ? $comparison : null,
            $logical
        ));

        return $this;
    }

    /**
     * @param null|string $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     * @return Sql
     */
    private function whereClause(?String $column, ?Comparison $comparison = null, ?Logical $logical = null)
    {
        $value = [];
        if (!empty($comparison)) {
            $value = array_merge($value, $comparison->build()->parameters());
        }

        if (!empty($logical)) {
            $value = array_merge($value, $logical->build()->parameters());
        }

        $sql = "";
        if (empty($this->query()->get(Sql::WHERE)) && (is_null($logical) || !in_array($logical->getOperator(), [Sql::AND,Sql::OR]))) {
            $sql .= Sql::WHERE . Sql::SQL_SPACE;

            if ($this->logical()->getNested()) {
                $sql .= Sql::SQL_BRACKET_OPEN . Sql::SQL_SPACE;
                $this->logical()->setNested(false);
            }
        }

        $sql .= !is_null($column) ? $column . Sql::SQL_SPACE : "";
        $sql .= !is_null($comparison) ? $comparison->build()->sql() : "";
        $sql .= !is_null($logical) ? $logical->build()->sql() : "";

        return new Sql($sql, $value);
    }
}
