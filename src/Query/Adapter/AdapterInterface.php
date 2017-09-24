<?php

namespace QueryMule\Query\Adapter;

use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\SelectInterface;
use QueryMule\Query\Table\TableInterface;

/**
 * Class AdapterInterface
 * @package QueryMule\Adapter
 */
interface AdapterInterface
{
    /**
     * @param array $cols
     * @param TableInterface $table
     * @return SelectInterface
     */
    public function select(array $cols = [],TableInterface $table = null) : SelectInterface;

    /**
     * @param Sql $sql
     */
    public function fetch(Sql $sql);

    /**
     * @param Sql $sql
     */
    public function fetchAll(Sql $sql);
}