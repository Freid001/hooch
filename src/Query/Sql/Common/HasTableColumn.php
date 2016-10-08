<?php  namespace freidcreations\QueryMule\Query\Sql\Common;
use freidcreations\QueryMule\Builder\Sql\Sql;
use freidcreations\QueryMule\Builder\Sql\Table;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDataTypeAttributeInterface;

/**
 * Class HasTableColumn
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
trait HasTableColumn
{
    /**
     * @var HasAccent
     */
    private $builder;

    /**
     * Make Table Column
     * @param QueryBuilderInterface $builder
     */
    public function makeTableColumn(QueryBuilderInterface $builder)
    {
        $this->builder = $builder;
    }

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
        array $columns,
        array $definitions,
        array $adapters = []
    ){
        $parameters = [];
        if($columns) {
            $keys = array_keys($columns);

            foreach ($columns as $name => $column) {
                if ($column instanceof TableColumnDataTypeAttributeInterface) {

                    //Add column name
                    sql::raw($key)->add($this->builder->accent($column->column_name));

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

    /**
     * Generate Constraint
     * @param $keys
     * @param $append
     * @param \Closure $add
     */
    public function generateConstraint($keys, $append,\Closure $add)
    {
        foreach($keys as $name=>$columns){
            if($append) {
                sql::raw(QueryBuilderInterface::CREATE_TABLE)->add(', ');
            }

            foreach($columns as &$row){
                $row = $this->builder->accent($row);
            }

            sql::raw(QueryBuilderInterface::CREATE_TABLE)->add(
                $add($name,implode(",",$columns))
            );
        }
    }
}