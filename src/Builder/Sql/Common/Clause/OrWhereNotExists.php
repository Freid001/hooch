<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Clause\OrWhereNotExistsInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

/**
 * Class OrWhereNotExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereNotExists implements OrWhereNotExistsInterface
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
     * OrWhereNotExists constructor.
     * @param OrWhereNotInterface $clause
     * @param Logical $logical
     */
    public function __construct(OrWhereNotInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param Sql $subQuery
     */
    public function orWhereNotExists(Sql $subQuery): void
    {
        $this->clause->orWhereNot(null, null, $this->logical->exists($subQuery));
    }
}
