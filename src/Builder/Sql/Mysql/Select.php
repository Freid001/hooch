<?php

namespace QueryMule\Builder\Sql\Mysql;

use QueryMule\Query\Repository\RepositoryInterface;
use QueryMule\Builder\Sql\Generic\Select as GenericSelect;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Sqlite
 */
class Select extends GenericSelect
{
    /**
     * Select constructor.
     * @param array $cols
     * @param RepositoryInterface|null $table
     */
    public function __construct(array $cols = [''], RepositoryInterface $table = null)
    {
        parent::__construct($cols, $table, '`');
    }
}
