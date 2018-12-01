<?php

namespace QueryMule\Query\Sql\Statement;

use QueryMule\Query\QueryBuilderInterface;
use QueryMule\Query\Sql\Clause\NestedWhereInterface;
use QueryMule\Query\Sql\Clause\OrWhereBetweenInterface;
use QueryMule\Query\Sql\Clause\OrWhereExistsInterface;
use QueryMule\Query\Sql\Clause\OrWhereInInterface;
use QueryMule\Query\Sql\Clause\OrWhereInterface;
use QueryMule\Query\Sql\Clause\OrWhereLikeInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotBetweenInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotExistsInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotInInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotInterface;
use QueryMule\Query\Sql\Clause\OrWhereNotLikeInterface;
use QueryMule\Query\Sql\Clause\WhereBetweenInterface;
use QueryMule\Query\Sql\Clause\WhereExistsInterface;
use QueryMule\Query\Sql\Clause\WhereInInterface;
use QueryMule\Query\Sql\Clause\WhereInterface;
use QueryMule\Query\Sql\Clause\WhereLikeInterface;
use QueryMule\Query\Sql\Clause\WhereNotBetweenInterface;
use QueryMule\Query\Sql\Clause\WhereNotExistsInterface;
use QueryMule\Query\Sql\Clause\WhereNotInInterface;
use QueryMule\Query\Sql\Clause\WhereNotInterface;
use QueryMule\Query\Sql\Clause\WhereNotLikeInterface;
use QueryMule\Query\Sql\Operator\Logical;

/**
 * Interface FilterInterface
 * @package QueryMule\Query\Sql\Statement
 */
interface FilterInterface extends NestedWhereInterface,
                                  OrWhereBetweenInterface,
                                  OrWhereExistsInterface,
                                  OrWhereInInterface,
                                  OrWhereInterface,
                                  OrWhereLikeInterface,
                                  OrWhereNotBetweenInterface,
                                  OrWhereNotExistsInterface,
                                  OrWhereNotInInterface,
                                  OrWhereNotInterface,
                                  OrWhereNotLikeInterface,
                                  WhereBetweenInterface,
                                  WhereExistsInterface,
                                  WhereInInterface,
                                  WhereInterface,
                                  WhereLikeInterface,
                                  WhereNotBetweenInterface,
                                  WhereNotExistsInterface,
                                  WhereNotInInterface,
                                  WhereNotInterface,
                                  WhereNotLikeInterface
{

    /**
     * @param bool $ignore
     * @return FilterInterface
     */
    public function ignoreAccent($ignore = true);
}