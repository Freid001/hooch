<?php namespace freidcreations\freidQuery\sql\statement;
use freidcreations\freidQuery\sql\table;
use freidcreations\freidQuery\sql\sql;

/**
 * @name delete
 * @author Fraser Reid
 * @created 26/11/2014
 * @copyright Copyright (c) - 2015 Fraser Reid
 */
class delete extends statement
{
    /**
     * Instance
     * @param table $table
     * @return mixed
     */
    public static function instance( table $table ){
        if ( !isset( static::$instances[self::DELETE.'_'.$table->name()] ) ) {
            self::$instances[self::DELETE.'_'.$table->name()] = new self( $table, self::DELETE );
        }
        return self::$instances[self::DELETE.'_'.$table->name()];
    }

    /**
     * Row
     * @return $this
     */
    public function row(){
        $this->parameters = [];
        $this->sql = self::DELETE . ' ' . self::FROM . ' ' . $this->table->name();
        return $this;
    }
}
?>