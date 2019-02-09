<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Builder\Sql\Mysql;


use Redstraw\Hooch\Query\Common\Sql\HasOn;
use Redstraw\Hooch\Query\Common\Sql\HasOrOn;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\OnFilterInterface;

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