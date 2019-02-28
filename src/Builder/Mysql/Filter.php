<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Mysql;


use Redstraw\Hooch\Query\Common\HasOperator;
use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Operator;
use Redstraw\Hooch\Query\Common\Filter\HasNestedWhere;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhere;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereBetween;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereExists;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereIn;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereLike;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereNot;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereNotBetween;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereNotExists;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereNotIn;
use Redstraw\Hooch\Query\Common\Filter\HasOrWhereNotLike;
use Redstraw\Hooch\Query\Common\Filter\HasWhere;
use Redstraw\Hooch\Query\Common\Filter\HasWhereBetween;
use Redstraw\Hooch\Query\Common\Filter\HasWhereExists;
use Redstraw\Hooch\Query\Common\Filter\HasWhereIn;
use Redstraw\Hooch\Query\Common\Filter\HasWhereLike;
use Redstraw\Hooch\Query\Common\Filter\HasWhereNot;
use Redstraw\Hooch\Query\Common\Filter\HasWhereNotBetween;
use Redstraw\Hooch\Query\Common\Filter\HasWhereNotExists;
use Redstraw\Hooch\Query\Common\Filter\HasWhereNotIn;
use Redstraw\Hooch\Query\Common\Filter\HasWhereNotLike;
use Redstraw\Hooch\Query\Accent;
use Redstraw\Hooch\Query\Query;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\FilterInterface;

/**
 * Class Filter
 * @package Redstraw\Hooch\Builder\Sql\Mysql
 */
class Filter implements FilterInterface
{
    use HasQuery;
    use HasOperator;
    use HasNestedWhere;
    use HasOrWhere;
    use HasOrWhereBetween;
    use HasOrWhereExists;
    use HasOrWhereIn;
    use HasOrWhereLike;
    use HasOrWhereNot;
    use HasOrWhereNotBetween;
    use HasOrWhereNotExists;
    use HasOrWhereNotIn;
    use HasOrWhereNotLike;
    use HasWhere;
    use HasWhereBetween;
    use HasWhereExists;
    use HasWhereIn;
    use HasWhereLike;
    use HasWhereNot;
    use HasWhereNotBetween;
    use HasWhereNotExists;
    use HasWhereNotIn;
    use HasWhereNotLike;

    /**
     * @var Accent
     */
    private $accent;

    /**
     * Filter constructor.
     * @param Query $query
     * @param Operator $operator
     */
    public function __construct(Query $query, Operator $operator)
    {
        $this->query = $query;
        $this->operator = $operator;
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::WHERE
    ]): Sql
    {
        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }
}
