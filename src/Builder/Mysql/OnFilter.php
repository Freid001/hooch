<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Mysql;


use Redstraw\Hooch\Query\Common\OnFilter\HasOn;
use Redstraw\Hooch\Query\Common\OnFilter\HasOrOn;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\OnFilterInterface;

/**
 * Class Join
 * @package Redstraw\Hooch\Builder\Sql\Mysql
 */
class OnFilter extends Filter implements OnFilterInterface
{
    use HasOn;
    use HasOrOn;

    /**
     * @param array $clauses
     * @return Sql
     */
    public function build(array $clauses = [
        Sql::JOIN
    ]): Sql
    {
        $sql = parent::build($clauses);

        $this->on = true;

        return $sql;
    }
}