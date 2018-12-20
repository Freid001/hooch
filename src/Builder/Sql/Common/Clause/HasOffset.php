<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Common\Clause;


use QueryMule\Builder\Sql\Common\Common;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasOffset
 * @package QueryMule\Builder\Sql\Common\Clause
 */
trait HasOffset
{
    use Common;

    /**
     * @param int $offset
     * @return $this
     */
    public function offset(int $offset)
    {
        $sql = new Sql(Sql::OFFSET);
        $sql->append($offset);

        $this->query()->add(Sql::OFFSET, $sql);

        return $this;
    }
}
