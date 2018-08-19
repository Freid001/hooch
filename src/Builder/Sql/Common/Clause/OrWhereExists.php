<?php

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Query\Sql\Clause\OrWhereBetweenInterface;
use QueryMule\Query\Sql\Clause\OrWhereExistsInterface;
use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Sql;

/**
 * Class OrWhereExists
 * @package QueryMule\Builder\Sql\Common\Clause
 */
class OrWhereExists implements OrWhereExistsInterface
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
     * OrWhereBetween constructor.
     * @param OrWhereInterface $clause
     * @param Logical $logical
     */
    public function __construct(OrWhereInterface $clause, Logical $logical)
    {
        $this->clause = $clause;
        $this->logical = $logical;
    }

    /**
     * @param Sql $subQuery
     */
    public function orWhereExists(Sql $subQuery): void
    {
        $this->clause->orWhere(null, null, $this->logical->exists($subQuery));
    }
}
