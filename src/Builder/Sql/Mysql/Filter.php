<?php

namespace QueryMule\Builder\Sql\Mysql;

use QueryMule\Builder\Sql\Generic\Filter as GenericFilter;

/**
 * Class Filter
 * @package QueryMule\Builder\Sql\Mysql
 */
class Filter extends GenericFilter
{
    /**
     * Filter constructor.
     */
    public function __construct()
    {
        parent::__construct('`');
    }
}