<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\HasWhereClause;
use QueryMule\Query\Sql\Clause\NestedWhereInterface;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\QueryClass;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\FilterInterface;

/**
 * Class NestedWhere
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class NestedWhere implements NestedWhereInterface
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
     * NestedWhere constructor.
     * @param QueryClass $query
     * @param Logical $logical
     */
    public function __construct(QueryClass $query, Logical $logical)
    {
        $this->query = $query;
        $this->logical = $logical;
    }

    /**
     * @param \Closure $column
     * @param FilterInterface $filter
     */
    public function nestedWhere(\Closure $column, FilterInterface $filter): void
    {
        $this->setNested(true);
        $this->logical->setNested(true);

        $column($filter);

        $this->query->add(Sql::WHERE, new Sql($this->setNested(true)->nested(false)));
    }
}
