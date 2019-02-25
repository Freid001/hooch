<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Sql;


use Redstraw\Hooch\Query\Exception\SqlException;
use Redstraw\Hooch\Query\Sql\Field;
use Redstraw\Hooch\Query\Sql\Sql;
use Redstraw\Hooch\Query\Sql\Statement\SelectInterface;

/**
 * Trait HasCols
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasCols
{
    /**
     * @param array $columns
     * @return SelectInterface
     * @throws SqlException
     */
    public function cols(array $columns = []): SelectInterface
    {
        if(empty($columns)){
            $columns = [Field::column(Sql::SQL_STAR)];
        }

        if($this instanceof SelectInterface) {
            $query = $this->query();

            $columnArray = array_reduce(array_keys($columns), function($transformed, $key) use ($query, $columns)
            {
                $column = $columns[$key];
                if($column instanceof Field\FieldInterface)
                {
                    $column->setAccent($query->accent());

                    if(is_string($key)){
                        array_push($transformed, $column->sql()->string() . Sql::SQL_SPACE . Sql::AS . Sql::SQL_SPACE . $query->accent()->append($key));
                    }else {
                        array_push($transformed, $column->sql()->string());
                    }
                }

                return $transformed;
            },[]);

            $query->sql()
                ->ifThenAppend($query->hasClause(Sql::COLS),",",[],false)
                ->ifThenAppend(!empty($columnArray),implode(",", $columnArray));

            $query->appendSqlToClause(Sql::COLS, false);

            return $this;
        }else {
            throw new SqlException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
