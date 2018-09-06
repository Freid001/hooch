<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Nested;
use QueryMule\Query\Sql\Operator\Comparison;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\QueryClass;
use QueryMule\Query\Sql\Sql;

/**
 * Class Filter
 * @package QueryMule\Builder\Sql\Common
 */
class Where implements WhereInterface
{
    /**
     * @var QueryClass
     */
    private $query;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * @var bool
     */
    private $nested;

    /**
     * Where constructor.
     * @param QueryClass $query
     * @param Logical $logical
     * @param Accent $accent
     * @param bool $nested
     */
    public function __construct(QueryClass $query, Logical $logical, Accent $accent, bool $nested = false)
    {
        $this->query = $query;
        $this->logical = $logical;
        $this->accent = $accent;
        $this->nested = $nested;
    }

    /**
     * @param $column
     * @param null|Comparison $comparison
     * @param null|Logical $logical
     */
    public function where($column, ?Comparison $comparison = null, ?Logical $logical = null): void
    {
        $column = is_string($column) ? $this->accent->addAccent($column, '.') : $column;

        $and = false;
        if ((is_null($logical) || $logical->getOperator() != Sql:: OR) && !empty($this->query->get(Sql::WHERE))) {
            $and = true;
            $logical = $this->logical->and($column, $comparison, $logical);
        }

        $this->query->add(Sql::WHERE, $this->whereClause(
            !$and ? $column : null,
            !$and ? $comparison : null,
            $logical
        ));
    }

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
            $sql .= ($this->nested) ? Sql::SQL_BRACKET_OPEN : "";
        }

        $sql .= !is_null($column) ? $column . Sql::SQL_SPACE : "";
        $sql .= !is_null($comparison) ? $comparison->build()->sql() : "";
        $sql .= !is_null($logical) ? $logical->build()->sql() : "";

        return new Sql($sql, $value);
    }
}
