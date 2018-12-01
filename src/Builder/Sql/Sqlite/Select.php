<?php

namespace QueryMule\Builder\Sql\Sqlite;

use QueryMule\Query\Repository\RepositoryInterface;

/**
 * Class Select
 * @package QueryMule\Builder\Sql\Sqlite
 */
abstract class Select
{
    /**
     * Select constructor.
     * @param array $cols
     * @param RepositoryInterface|null $table
     */
    public function __construct(array $cols = [], RepositoryInterface $table = null)
    {
        //parent::__construct($cols, $table, '`');
    }
}
