<?php

declare(strict_types=1);

namespace QueryMule\Builder\Sql\Mysql;


use QueryMule\Query\Common\Sql\HasOn;
use QueryMule\Query\Common\Sql\HasOrOn;
use QueryMule\Query\Sql\Sql;
use QueryMule\Query\Sql\Statement\OnFilterInterface;

/**
 * Class Join
 * @package QueryMule\Builder\Sql\Mysql
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

        return $sql;
    }
}