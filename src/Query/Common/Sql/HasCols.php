<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasCols
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasCols
{
    /**
     * @var int
     */
    private $columnIndex = 0;

    /**
     * @var array
     */
    private $columnKeys = [];

    /**
     * @param array $columns
     * @param string|null $alias
     * @return SelectInterface
     * @throws SqlException
     */
    public function cols(array $columns = [Sql::SQL_STAR], ?string $alias = null): SelectInterface
    {
        if($this instanceof SelectInterface) {

            $query = $this->query();

            $keys = array_keys($columns);
            $columnsWithAs = array_reduce($keys, function($transformed,$key) use ($query, $columns)
            {
                $column = $query->accent()->append($columns[$key]);
                if(is_string($key)){
                    array_push($transformed, $column . Sql::SQL_SPACE . Sql::AS . Sql::SQL_SPACE . $key);
                }else {
                    array_push($transformed, $column);
                }

                return $transformed;
            },[]);

            $columnsWithAlias = array_reduce($columnsWithAs, function($transformed,$column) use ($query, $alias)
            {
                $columnString = '';
                if($alias){
                    $columnString .= $query->accent()->append($alias);
                    $columnString .= '.';
                }

                $columnString .= $column;

                array_push($transformed, $columnString);

                return $transformed;
            },[]);

            $query->sql()
                ->ifThenAppend($query->hasClause(Sql::COLS),",",[],false)
                ->append(implode(",", $columnsWithAlias));

            $query->toClause(Sql::COLS, false);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
