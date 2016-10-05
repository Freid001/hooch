<?php namespace freidcreations\QueryMule\Query\Sql\Common;

/**
 * Interface TableColumnInterface
 * @package freidcreations\QueryMule\Query\Sql\Common
 */
interface TableColumnDefinitionInterface
{
    /**
     * Has Attribute
     * @param $key
     * @return bool
     */
    public function hasAttribute($key);

    /**
     * Has Parameter
     * @param $key
     * @return bool
     */
    public function hasParameter($key);

    /**
     * Get
     * @param $key
     * @return string
     */
    public function getAttribute($key);

    /**
     * Get Parameter
     * @param $key
     * @return array
     */
    public function getParameter($key);

    /**
     * Nullable
     * @return $this
     */
    public function nullable();

    /**
     * Not Null
     * @return $this
     */
    public function notNull();

    /**
     * Default
     * @param $value
     * @return $this
     */
    public function default($value);

    /**
     * Comment
     * @param $comment
     * @return $this
     */
    public function comment($comment);

    /**
     * After
     * @param $column
     * @throws \Exception
     */
    public function after($column);

    /**
     * First
     * @throws \Exception
     */
    public function first();
}