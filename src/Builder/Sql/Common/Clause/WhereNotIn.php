<?php

namespace QueryMule\Builder\Sql\Common\Clause;

use QueryMule\Query\Sql\Clause\WhereNotInInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Clause\WhereNotInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Class OrWhereNotIn
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereNotIn implements WhereNotInInterface
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
     * @param WhereNotInterface $clause
     * @param Logical $logical
     */
    public function __construct(WhereNotInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param $column
     * @param array $values
     */
    public function whereNotIn($column, array $values = []): void
    {
        $this->clause->whereNot($column, null, $this->logical->in($values));
    }
}
