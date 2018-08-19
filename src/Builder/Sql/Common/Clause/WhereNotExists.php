<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\WhereNotExistsInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Clause\WhereNotInterface;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

/**
 * Class OrWhereNotExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class WhereNotExists implements WhereNotExistsInterface
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
     * @param WhereNotInterface $clause
     * @param Logical $logical
     */
    public function __construct(WhereNotInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param Sql $subQuery
     */
    public function whereNotExists(Sql $subQuery): void
    {
        $this->clause->whereNot(null, null, $this->logical->exists($subQuery));
    }
}
