<?php  namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeAdapterInterface;

/**
 * Class HasTableColumn
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
trait HasTableColumn
{
    use HasAccent;

    /**
     * Generate Columns
     * @param string $key
     * @param Table $table
     * @param array $columns
     * @param array $definitions
     * @param array $adapters
     * @return array
     */
    public function generateColumns(
        $key,
        Table $table,
        array $columns,
        array $definitions,
        array $adapters = []
    )
    {
        $parameters = [];
        if($columns) {
            $keys = array_keys($columns);

            foreach ($columns as $name => $column) {
                if ($column instanceof TableColumnDataTypeAttribute) {

                    //Add column name
                    sql::raw($key)->add($this->addAccent($table,$column->column_name));

                    //Add data type or use data type adapter
                    if(isset($adapters[$column->method])) {
                        $adapter = $adapters[$column->method];
                        if($adapter instanceof TableColumnDataTypeAdapterInterface) {
                            $adapter->setTableColumnDataTypeAttribute($column);

                            $column = $adapter;
                        }
                    }

                    //Add data type
                    if ($column->hasAttribute('parameter')) {
                        sql::raw($key)->add($column->data_type . "(" . implode(",", $column->parameter) . ")");
                    }else {
                        sql::raw($key)->add($column->data_type);
                    }

                    //Anything to run after?
                    if ($column->hasAttribute('after')) {
                        sql::raw($key)->add($column->after);
                    }

                    //Add column definition
                    foreach ($definitions as $attribute) {

                        //Has Attribute?
                        if ($column->definition()->hasAttribute($attribute)) {
                            sql::raw($key)->add($column->definition()->getAttribute($attribute));

                            //Has Parameters?
                            if ($column->definition()->hasParameter($attribute)) {
                                $parameters[] = $column->definition()->getParameter($attribute);
                            }
                        }
                    }

                    //Do we need to add a comer?
                    if ($name != $keys[count($keys) - 1]) {
                        sql::raw($key)->add(', ');
                    }
                }
            }
        }

        return $parameters;
    }
}