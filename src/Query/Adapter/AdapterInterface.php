<?php

namespace QueryMule\Query\Adapter;

use QueryMule\Query\Sql\SelectInterface;
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
}