<?php
namespace freidcreations\freidQuery\sql\statement;
use freidcreations\freidQuery\sql\table;

/**
 * @name update
 * @author Fraser Reid
 * @created 26/11/2014
 * @copyright Copyright (c) - 2015 Fraser Reid
 */
class update extends statement
{
    private $dataSet;

    /**
     * Instance
     * @param table $table
     * @return mixed
     */
    public static function instance( table $table ){
        if ( !isset( static::$instances[self::UPDATE.'_'.$table->name()] ) ) {
            self::$instances[self::UPDATE.'_'.$table->name()] = new self( $table, self::UPDATE );
        }
        return self::$instances[self::UPDATE.'_'.$table->name()];
    }

    /**
     * Set
     * @param array $cols
     * @return $this
     */
    public function set( array $cols )
    {
        $this->dataSet = [];

        //DATA SET
        foreach ( $cols as $key => $value ) {
            $this->dataSet[] = $key . ' =?';
            $this->parameters[] = $value;
        }

        //SQL
        $this->sql = self::UPDATE . ' ' . $this->table->name() . ' ' . self::SET . ' '. implode( ',', $this->dataSet );
        return $this;
    }
}
?>