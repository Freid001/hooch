<?php namespace freidcreations\QueryMule\Table;
use freidcreations\QueryMule\Query\Table\AbstractTable;

/**
 * Simple Table
 * @author Fraser Reid
 * @created 01/03/2014
 */
class Simple extends AbstractTable
{
    /**
     * @param $table
     * @return \freidcreations\freidQuery\sql\table|AbstractTable
     */
    public static function query($table){
        return parent::table($table);
    }
}