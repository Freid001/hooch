<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;

/**
 * Trait HasLimit
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasLimit
{
    use Common;

    /**
     * @param int $limit
     * @return $this
     */
    public function limit(int $limit)
    {
        $sql = new Sql(Sql::LIMIT);
        $sql->append($limit);

        $this->query()->append(Sql::LIMIT, $sql);

        return $this;
    }
}
