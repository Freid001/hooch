<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\NestedWhereInterface;
use QueryMule\Query\Sql\Nested;
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
    //use HasWhereClause;
    use Nested;

    /**
     * @var QueryClass
     */
    private $query;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * @var FilterInterface
     */
    private $filter;

    /**
     * NestedWhere constructor.
     * @param QueryClass $query
     * @param Logical $logical
     * @param FilterInterface $filter
     */
    public function __construct(QueryClass $query, Logical $logical, FilterInterface $filter)
    {
        $this->query = $query;
        $this->logical = $logical;
        $this->filter = $filter;
    }

    /**
     * @param \Closure $callback
     */
    public function nestedWhere(\Closure $callback): void
    {
        call_user_func($callback, $query = $this->filter);

        $this->query->add(Sql::WHERE, new Sql($this->setNested(true)->nested(false)));
    }
}
