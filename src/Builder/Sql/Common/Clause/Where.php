<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Clause\WhereInterface;
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
    use HasWhereClause;

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
     * Where constructor.
     * @param QueryClass $query
     * @param Logical $logical
     * @param Accent $accent
     */
    public function __construct(QueryClass $query, Logical $logical, Accent $accent)
    {
        $this->query = $query;
        $this->logical = $logical;
        $this->accent = $accent;
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
}
