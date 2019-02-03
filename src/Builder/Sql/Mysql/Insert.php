<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Query\Common\HasQuery;
use QueryMule\Query\Common\Sql\HasInsert;
use QueryMule\Query\Common\Sql\HasInto;
use QueryMule\Query\Common\Sql\HasOnDuplicateKeyUpdate;
use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Query\Sql\Query;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\InsertInterface;

/**
 * Class Insert
 * @package QueryMule\Builder\Sql\Mysql
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
