<?php namespace freidcreations\QueryMule\Builder\Sql\Common;
use freidcreations\QueryMule\Query\Sql\Common\TableColumnDefinitionInterface;

/**
 * Class TableColumnDefinition
 * @package freidcreations\QueryMule\Builder\Sql\Common
 */
class TableColumnDefinition implements TableColumnDefinitionInterface
{
    const NULL = 'NULL';
    const NOTNULL = 'NOT NULL';
    const DEFAULT = 'DEFAULT';
    const AUTO_INCREMENT = 'AUTO_INCREMENT';
    const PRIMARY_KEY = 'PRIMARY KEY';
    const UNIQUE_KEY = 'UNIQUE KEY';
    const COMMENT = 'COMMENT';
    const INDEX = 'INDEX';

    /**
     * @var array
     */
    private $attributes = [];

    /**
     * @var array
     */
    private $parameters = [];

    /**
     * @var bool
     */
    private $type = false;

    /**
     * TableColumn constructor.
     * @param $name
     * @param $dataType
     * @param string $type
     */
    public function __construct($name, $dataType, $type)
    {
        $this->attributes['name'] = $name;
        $this->attributes['data_type'] = $dataType;
        $this->type = $type;
    }

    /**
     * Has Attribute
     * @param $key
     * @return bool
     */
    public function hasAttribute($key)
    {
        return  isset($this->attributes[$key]);
    }

    /**
     * Has Parameter
     * @param $key
     * @return bool
     */
    public function hasParameter($key)
    {
        return  isset($this->parameters[$key]);
    }

    /**
     * Get
     * @param $key
     * @return string
     */
    public function getAttribute($key)
    {
        if(isset($this->attributes[$key])){
            return $this->attributes[$key];
        }
        return '';
    }

    /**
     * Get Parameter
     * @param $key
     * @return array
     */
    public function getParameter($key)
    {
        if(isset($this->parameters[$key])){
            return $this->parameters[$key];
        }
        return '';
    }

    /**
     * Nullable
     * @return $this
     */
    public function nullable()
    {
        $this->attributes['null'] = self::NULL;
        return $this;
    }

    /**
     * Not Null
     * @return $this
     */
    public function notNull()
    {
        $this->attributes['not_null'] = self::NOTNULL;
        return $this;
    }

    /**
     * Default
     * @param $value
     * @return $this
     */
    public function default($value)
    {
        $this->attributes['default'] = self::DEFAULT . " ?";
        $this->parameters['default'] = $value;
        return $this;
    }

    /**
     * AutoIncrement
     * @return $this
     */
    public function autoIncrement()
    {
        $this->attributes['auto_increment'] = self::AUTO_INCREMENT;
        return $this;
    }

    /**
     * Comment
     * @param $comment
     * @return $this
     */
    public function comment($comment)
    {
        $this->attributes['comment'] = self::COMMENT . " ?";
        $this->parameters['comment'] = $comment;
        return $this;
    }

    /**
     * After
     * @param $column
     * @throws \Exception
     */
    public function after($column)
    {
        if(!in_array($this->type, [
            'add',
            'modify'
        ])){
            throw new \Exception('Can only be used with alter: add or modify.');
        }

        $this->attributes['after'] = 'AFTER ' . $column;
    }

    /**
     * First
     * @throws \Exception
     */
    public function first()
    {
        if(!in_array($this->type,[
            'add',
            'modify'
        ])){
            throw new \Exception('Can only be used with alter: add or modify.');
        }

        $this->attributes['first'] = 'FIRST';
    }

    public function drop()
    {

    }
}