<?php

declare(strict_types=1);

namespace Redstraw\Hooch\Query\Common\Select;


use Redstraw\Hooch\Query\Exception\InterfaceException;
use Redstraw\Hooch\Query\Field;
use Redstraw\Hooch\Query\Sql;
use Redstraw\Hooch\Query\Statement\SelectInterface;

/**
 * Trait HasCols
 * @package Redstraw\Hooch\Query\Common\Sql
 */
trait HasCols
{
    /**
     * @param array $columns
     * @return SelectInterface
     * @throws InterfaceException
     */
    public function cols(array $columns = []): SelectInterface
    {
        if(empty($columns)){
            $columns = [Field::column(Sql::SQL_STAR)];
        }

        if($this instanceof SelectInterface) {
            $query = $this->query();
            $columnArray = array_reduce(array_keys($columns), function($transformed, $key) use ($query, $columns) {
                $column = $columns[$key];
                if($column instanceof Field\FieldInterface) {
                    $column->setAccent($query->accent());

                    if(is_string($key)){
                        array_push($transformed, $column->sql()->queryString() . Sql::SQL_SPACE . Sql::AS . Sql::SQL_SPACE . $query->accent()->append($key));
                    }else {
                        array_push($transformed, $column->sql()->queryString());
                    }
                }

                return $transformed;
            },[]);

            $this->query()->clause(Sql::COLS, function (Sql $sql) use ($query, $columnArray) {
                return $sql
                    ->ifThenAppend($query->hasClause(Sql::COLS),",",[],false)
                    ->ifThenAppend(!empty($columnArray),implode(",", $columnArray));

            });

            return $this;
        }else {
            throw new InterfaceException(sprintf("Must invoke SelectInterface in: %s.", get_class($this)));
        }
    }
}
