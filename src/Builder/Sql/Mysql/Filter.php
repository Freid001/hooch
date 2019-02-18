<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Sql\Mysql;


use Redstraw\Hooch\Query\Common\HasOperator;
use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Common\Operator\Operator;
use Redstraw\Hooch\Query\Common\Sql\HasNestedWhere;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhere;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereBetween;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereExists;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereIn;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereLike;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereNot;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereNotBetween;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereNotExists;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereNotIn;
use Redstraw\Hooch\Query\Common\Sql\HasOrWhereNotLike;
use Redstraw\Hooch\Query\Common\Sql\HasWhere;
use Redstraw\Hooch\Query\Common\Sql\HasWhereBetween;
use Redstraw\Hooch\Query\Common\Sql\HasWhereExists;
use Redstraw\Hooch\Query\Common\Sql\HasWhereIn;
use Redstraw\Hooch\Query\Common\Sql\HasWhereLike;
use Redstraw\Hooch\Query\Common\Sql\HasWhereNot;
use Redstraw\Hooch\Query\Common\Sql\HasWhereNotBetween;
use Redstraw\Hooch\Query\Common\Sql\HasWhereNotExists;
use Redstraw\Hooch\Query\Common\Sql\HasWhereNotIn;
use Redstraw\Hooch\Query\Common\Sql\HasWhereNotLike;
use Redstraw\Hooch\Query\Sql\Accent;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\FilterInterface;

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
