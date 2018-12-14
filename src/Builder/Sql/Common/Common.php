<?php

declare(strict_types=1);

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
    abstract protected function query(): Query;

    /**
     * @return Logical
     */
    abstract protected function logical(): Logical;

    /**
     * @return Accent
     */
    abstract protected function accent(): Accent;

}