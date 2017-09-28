<?php namespace freidcreations\QueryMule\Builder\Sql;

/**
 * @name Sql
 * @author Fraser Reid
 * @created 26/11/2015
 * @copyright Copyright (c) - 2015 Fraser Reid
 */
class Sql
{
    /**
     * @var array
     */
    private static $instances = [];

    /**
     * @var string
     */
    private $sql;

    /**
     * @var array
     */
    private $parameters;

    /**
     * Get Instance
     * @param $type
     * @return self
     */
    public static function raw($type = null)
    {
        if(!is_null($type)) {
            if (!isset( static::$instances[$type])) {
                self::$instances[$type] = new self();
            }
            return self::$instances[$type];
        }
        return new self();
    }

    /**
     * Reset
     * @param string $type
     */
    public static function reset($type = 'all')
    {
        if($type == 'all'){
            foreach(self::$instances as $key => $instance){
                if($instance instanceof self) {
                    $instance->reset( $key );
                }
            }
        }elseif(isset(self::$instances[$type])){
            self::$instances[$type]->sql = null;
            self::$instances[$type]->parameters = null;
        }
    }

    /**
     * Add
     * @param null $sql
     * @param array $parameters
     * @return $this
     */
    public function add($sql = null, array $parameters = [])
    {
        if(!empty($sql)){
            $this->sql .= $sql . ' ';
        }

        foreach($parameters as $key => $parameter){
            $this->parameters[] = $parameter;
        }

        return $this;
    }

    /**
     * Print Pritty
     */
    public function printPritty()
    {
        echo '<pre>';
        print_r( $this );
        echo '</pre>';
    }

    /**
     * SQL
     * @return string
     */
    public function sql()
    {
        return !empty( $this->sql ) ? $this->sql : '';
    }

    /**
     * Parameters
     * @return array
     */
    public function parameters()
    {
        return !empty( $this->parameters ) ? $this->parameters : [];
    }
}