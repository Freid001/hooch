<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Nested;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;

/**
 * Class Filter
 * @package QueryMule\Builder\Sql\Common
 */
class Where implements WhereInterface
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * Where constructor.
     * @param Query $query
     * @param Logical $logical
     */
    public function __construct(Query $query, Logical $logical)
    {
        $this->query = $query;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null): void
    {
        $logical = is_null($logical) ? new Logical() : $logical;

        if (!empty($this->query->get(Sql::WHERE))) {
            if (empty($logical->getOperator()) || $logical->getOperator() == Sql::AND) {
                if ($this->logical->getNested()) {
                    $logical->setNested(true)->and($column, $comparison, $logical);
                    $this->logical->setNested(false);
                } else {
                    $logical->and($column, $comparison, $logical);
                }
            }
        }

//print_r($logical->getOperator());
//print_r(!$logical->getOperator());
//print_r($column);

        $this->query->add(Sql::WHERE, $this->whereClause(
            !$logical->getOperator() ? $column : null,
            !$logical->getOperator() ? $comparison : null,
            $logical
        ));
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
        if (is_null($logical) || !in_array($logical->getOperator(), [Sql::AND,Sql::OR])) {
            $sql .= Sql::WHERE . Sql::SQL_SPACE;

            if ($this->logical->getNested()) {
                $sql .= Sql::SQL_BRACKET_OPEN . Sql::SQL_SPACE;
                $this->logical->setNested(false);
            }
        }

        $sql .= !is_null($column) ? $column . Sql::SQL_SPACE : "";
        $sql .= !is_null($comparison) ? $comparison->build()->sql() : "";
        $sql .= !is_null($logical) ? $logical->build()->sql() : "";

        return new Sql($sql, $value);
    }
}
