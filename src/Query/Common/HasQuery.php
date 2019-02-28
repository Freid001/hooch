<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common;


use Redstraw\Hooch\Query\Query;

/**
 * Trait HasQuery
 * @package Redstraw\Hooch\Query\Common
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
