<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Sql\Mysql;


use Redstraw\Hooch\Query\Common\HasQuery;
use Redstraw\Hooch\Query\Common\Sql\HasInsert;
use Redstraw\Hooch\Query\Common\Sql\HasInto;
use Redstraw\Hooch\Query\Common\Sql\HasOnDuplicateKeyUpdate;
use Redstraw\Hooch\Query\Repository\RepositoryInterface;
use Redstraw\Hooch\Query\Sql\Query;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\InsertInterface;

/**
 * Class Insert
 * @package Redstraw\Hooch\Builder\Sql\Mysql
 */
class Insert implements InsertInterface
{
    use HasQuery;
    use HasInto;
    use HasInsert;
    use HasOnDuplicateKeyUpdate;

    /**
     * @var Query
     */
    private $query;

    /**
     * Insert constructor.
     * @param Query $query
     */
    public function __construct(Query $query)
    {
        $this->query = $query;
        $this->query->append(Sql::INSERT, $this->query->sql()->append(Sql::INSERT));
    }

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::INSERT
    ]): Sql
    {
        $sql = $this->query->build($clauses);

        $this->query->reset($clauses);

        return $sql;
    }
}
