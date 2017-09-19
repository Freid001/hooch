<?php

namespace QueryMule\Query\Table;

/**
 * Interface TableInterface
 * @package QueryMule\Query\Table
 */
interface TableInterface
{
    /**
     * @return string
     */
    public function getTableName() : string;
}