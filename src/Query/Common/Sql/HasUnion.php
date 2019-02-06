<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\QueryBuilderInterface;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasUnion
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasUnion
{
    /**
     * @param QueryBuilderInterface $select
     * @param bool $all
     * @return SelectInterface
     * @throws SqlException
     */
    public function union(QueryBuilderInterface $select, bool $all = false): SelectInterface
    {
        if($this instanceof SelectInterface){
            $sql = $this->query()->sql();

            $sql->append(Sql::UNION)
                ->ifThenAppend(!empty($all), Sql::ALL)
                ->append($select->build());

            $this->query()->append(Sql::UNION, $sql);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
