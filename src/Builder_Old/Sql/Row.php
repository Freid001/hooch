<?php
namespace freidcreations\QueryMule\Sql;

/**
 * @name Row
 * @author Fraser Reid
 * @created 26/11/2015
 * @copyright Copyright (c) - 2016 Fraser Reid
 */
class Row
{
    /**
     * @var array
     */
    private $data = [];

    public function columns(){
        return array_keys( $this->data );
    }

    /**
     * Set
     * @param $key
     * @param $value
     */
    public function __set( $key, $value = null ){
        $this->data[ $key ] = $value;
    }

    /**
     * Get
     * @param $key
     * @return null
     */
    public function __get( $key ){
        if( array_key_exists( $key, $this->data ) ) {
            return $this->data[$key];
        }
        return null;
    }

    /**
     * Save
     * @param array $with
     */
    public function save( array $with = [] ){

    }
}
