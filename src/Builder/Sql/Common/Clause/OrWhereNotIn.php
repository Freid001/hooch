<?php

namespace QueryMule\Builder\Sql\Common\Clause;



use QueryMule\Query\Sql\Clause\OrWhereNotInInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereNotIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereNotIn implements OrWhereNotInInterface
{
    /**
     * @var WhereInterface
     */
    private $clause;

    /**
     * @var Logical
     */
    private $logical;

    /**
     * OrWhereNotIn constructor.
     * @param OrWhereNotInterface $clause
     * @param Logical $logical
     */
    public function __construct(OrWhereNotInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param array $values
     */
    public function orWhereNotIn($column, array $values = []): void
    {
        $this->clause->orWhereNot($column, null, $this->logical->in($values));
    }
}
