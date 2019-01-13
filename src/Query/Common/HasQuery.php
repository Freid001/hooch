<?php

declare(strict_types=1);

namespace QueryMule\Query\Common;


use QueryMule\Query\Sql\Query;

/**
 * Trait HasQuery
 * @package QueryMule\Query\Common
 */
trait HasQuery
{
    /**
     * @var Query
     */
    private $query;

    /**
     * @return Query
     */
    public function query(): Query
    {
        return $this->query;
    }
}
