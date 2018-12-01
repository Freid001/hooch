<?php

namespace QueryMule\Builder\Sql\Common;

use QueryMule\Query\Sql\Accent;
use QueryMule\Query\Sql\Operator\Logical;
use QueryMule\Query\Sql\Query;

/**
 * Trait Common
 * @package QueryMule\Builder\Sql\Common
 */
trait Common
{
    /**
     * @return Query
     */
    abstract public function query(): Query;

    /**
     * @return Logical
     */
    abstract public function logical(): Logical;

    /**
     * @return Accent
     */
    abstract public function accent(): Accent;

}